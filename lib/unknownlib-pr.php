<?php

//convert a string to a 32-bit integer
function unknownlib_pr_StrToNum($Str, $Check, $Magic) {
    $Int32Unit = 4294967296;  // 2^32

    $length = strlen($Str);
    for ($i = 0; $i < $length; $i++) {
        $Check *= $Magic; 	
        //If the float is beyond the boundaries of integer (usually +/- 2.15e+9 = 2^31), 
        //  the result of converting to integer is undefined
        //  refer to http://www.php.net/manual/en/language.types.integer.php
        if ($Check >= $Int32Unit) {
            $Check = ($Check - $Int32Unit * (int) ($Check / $Int32Unit));
            //if the check less than -2^31
            $Check = ($Check < -2147483648) ? ($Check + $Int32Unit) : $Check;
        }
        $Check += ord($Str{$i}); 
    }
    return $Check;
}

//genearate a hash for a url
function unknownlib_pr_HashURL($String) {
    $Check1 = unknownlib_pr_StrToNum($String, 0x1505, 0x21);
    $Check2 = unknownlib_pr_StrToNum($String, 0, 0x1003F);

    $Check1 >>= 2; 	
    $Check1 = (($Check1 >> 4) & 0x3FFFFC0 ) | ($Check1 & 0x3F);
    $Check1 = (($Check1 >> 4) & 0x3FFC00 ) | ($Check1 & 0x3FF);
    $Check1 = (($Check1 >> 4) & 0x3C000 ) | ($Check1 & 0x3FFF);	
	
    $T1 = (((($Check1 & 0x3C0) << 4) | ($Check1 & 0x3C)) <<2 ) | ($Check2 & 0xF0F );
    $T2 = (((($Check1 & 0xFFFFC000) << 4) | ($Check1 & 0x3C00)) << 0xA) | ($Check2 & 0xF0F0000 );
	
    return ($T1 | $T2);
}

//genearate a checksum for the hash string
function unknownlib_pr_CheckHash($Hashnum) {
    $CheckByte = 0;
    $Flag = 0;

    $HashStr = sprintf('%u', $Hashnum) ;
    $length = strlen($HashStr);
	
    for ($i = $length - 1;  $i >= 0;  $i --) {
        $Re = $HashStr{$i};
        if (1 === ($Flag % 2)) {              
            $Re += $Re;     
            $Re = (int)($Re / 10) + ($Re % 10);
        }
        $CheckByte += $Re;
        $Flag ++;	
    }

    $CheckByte %= 10;
    if (0 !== $CheckByte) {
        $CheckByte = 10 - $CheckByte;
        if (1 === ($Flag % 2) ) {
            if (1 === ($CheckByte % 2)) {
                $CheckByte += 9;
            }
            $CheckByte >>= 1;
        }
    }

    return '7'.$CheckByte.$HashStr;
}

//return the pagerank checksum hash
function unknownlib_pr_getch($url) { return unknownlib_pr_CheckHash(unknownlib_pr_HashURL($url)); }

//return the pagerank figure
function unknownlib_pr_getpr($url) {
	$opts = array('http' => array('user_agent'=>'Mozilla/4.0 (compatible; GoogleToolbar 2.0.114-big; Windows XP 5.1)'));
	$context  = stream_context_create($opts);
	$result = file_get_contents('http://toolbarqueries.google.com/search?client=navclient-auto&ch='.unknownlib_pr_getch($url).'&ie=UTF-8&oe=UTF-8&features=Rank&q=info:'.$url, false, $context);
	if(!preg_match('#^Rank_[0-9]:[0-9]:[0-9]$#',$result))
		return -1;
	else
		return preg_replace('#^Rank_[0-9]:[0-9]:([0-9])$#','$1',$result);
}

//generate the graphical pagerank
function unknownlib_pr_pagerank($url,$width=40) {
	if (!preg_match('/^(https?:\/\/)?([^\/]+)/i', $url)) { $url='http://'.$url; }
	$pr=unknownlib_pr_getpr($url);
	$pagerank='PageRank: '.$pr.'/10';
	$prpercent=100*$pr/10;
	$html='<span style="font-size:x-small;">PageRank: '.$pr.'/10<br /></span><span style="display:block;position: relative; width: '.$width.'px; padding: 0; background: #D9D9D9;" title="PageRank: '.$pr.'/10"><strong style="width: '.$prpercent.'%; display: block; position: relative; background: #5EAA5E; text-align: center; color: #333; height: 4px; line-height: 4px;"><span></span></strong></span>';
	return $html;
}

?>