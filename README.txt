================ Target ====================
The target it's have site with good performance to work on all hosting
and minimize the runing cost. You have with normal cpu and lighttpd:
40000req/s for billion of visitors by day WITH the cache enabled.
You have an litle admin to rewrite the product.

============ Runing example ================
You have some runing example, this base is exacted from it:
http://www.calle-hardware.com/
http://comparatif-btc.first-world.info/

============ How install it ================
Import the .sql into your base.
Customize the file into: config/
Setup you host, for lighttpd:
$HTTP["host"] == "www.site.com" {
       server.document-root = "/home/site"
       server.error-handler-404 = "/php/engine-comparatif.php"
}
For other do normal hosts and point the 404 page on /php/engine-comparatif.php
That's all.

============ Enable the cache ==============
You need enable into the config file
And add the cron:
10      *       *       *       *       /bin/rm -f /home/comparatif/comparatif-btc/js/news-content.js > /dev/null 2>&1
28      *       *       *       *       /usr/bin/nice -n 19 /usr/bin/ionice -c 3 /usr/bin/find /home/comparatif/ -type f -iname '*.html' -mmin +30 -exec rm -f {} \;

========= Module and auto-import ===========
Your need have setup the cron:
0       5       *       *       *       cd /home/comparatif/comparatif-btc/php/cron/ && /usr/bin/nice -n 19 /usr/bin/ionice -c 3 /usr/bin/php import_main.php > /dev/null 2>&1
You need have into:
php/cron/shop_plugin/
The plugin to import and the file name match with the colomn "url_alias_for_seo" into "shop" table.
You can buy plugin to import content from prestashop, magento, pixmania, ... and product technical information on:
http://shop.first-world.info/
You can write your self, but it's very complicated.

================ Licence ====================
The code is under GPL3, but the image have their own licence (mostly from oxygen theme for the icon).
The bar image is under GPL3.

============== Donation or work ==============
If you want give me some money to thanks to me:
Paypal: brule-herman@first-world.info
Bitcoin: 1FF6CfM59QsKf1c9Ve8cE13bL5kd9gkKgz
You can too contract me to work for you on this project (custom plugin, style, ...)
