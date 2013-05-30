<?php
/** \note Need include before the unknownlib-function.php **/
include_once 'unknownlib-function.php';
/// declare the module in global unknownlib system management
$GLOBALS['unknownlib']['modules']['session']=true;

/** \brief Do pagination like 1 2 ... 5 6 7 ... 998 999
Return 0 for ..., else the number
*/
function unknownlib_pagination_with_dot($number_item,$item_per_page,$current_page)
{
	$array_pagination=array();
	$max_page=unknownlib_pagination_max_page($number_item,$item_per_page);
	$a=1;
	while($a<=$max_page)
	{
		if(($a>2 && $a<($current_page-1)) || ($a>($current_page+1) && $a<($max_page-1)))
		{
			if(!$espace)
			{
				if($a>2 && $a<($current_page-1))
				{
					$array_pagination[]=0;
					$espace=true;
					$a=$current_page-2;
				}
				else
				{
					$array_pagination[]=0;
					$espace=true;
					$a=$max_page-2;
				}
			}
		}
		else
		{
			$espace=false;
			$array_pagination[]=$a;
		}
		$a++;
	}
	return $array_pagination;
}

//return 0 when no item
function unknownlib_pagination_max_page($number_item,$item_per_page)
{
	$max_page=ceil($number_item/$item_per_page);
	return $max_page;
}

?>