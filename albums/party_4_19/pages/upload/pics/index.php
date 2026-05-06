<?php

// Define your username and password
$username = "yingying";
$password = "yiyi";

if ($_POST['txtUsername'] != $username || $_POST['txtPassword'] != $password) {

?>

<h1>Login</h1>

<form name="form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <p><label for="txtUsername">Username:</label>
    <br /><input type="text" title="Enter your Username" name="txtUsername" /></p>

    <p><label for="txtpassword">Password:</label>
    <br /><input type="password" title="Enter your password" name="txtPassword" /></p>

    <p><input type="submit" name="Submit" value="Login" /></p>

</form>

<?php

}
else {

?>
<?php
error_reporting(E_ALL);
# config ###########################################################
$root='./';  // where to browse, ./ means wherever this script is located in
$rurl='./';  // url to $root, url equivalent of the path. ./ will be the same, relative
$burl='index.php?'; // base url, the url to access the indexer in case you are including this script into another one
$surl='index.php'; // the absolute url to this script so that it is linked as an image. ex: http://mydomain.com/some/where/out/there/index.php
$incl=0; // are you including this page into another one? 1 or yes, 0 for no. Yes means the script will print out <html><head...etc. No means the script will only print out the table.
$name='Zhoudynasty'; // name?
# css styles ######################################################
$css=<<<EOT
body,td{font-family:tahoma,verdana,arial;font-size:11px;line-height:15px;background-color:white;color:#666666;margin-left:20px;}
strong{font-size:12px;}
a:link{color:#0066CC;}
a:hover{color:#FF6600;}
a:visited{color:#003366;}
a:active{color:#9DCC00;}
table.itable{}
td.irows{height:20px;background:url("index.php?i=dots") repeat-x bottom}
EOT;
# end config #######################################################
function e($s){$p=strrpos($s,'.');return substr($s,$p+1,strlen($s));}
function s ($a,$c,$o='asc',$t=SORT_STRING){if(!isset($a[0][$c]))return $a;for ($i=0;$i<count($a);$i++)$temp[$i]=&$a[$i][$c];$o=($o=='asc')?SORT_ASC:SORT_DESC;array_multisort($temp,$o,$t,$a);return $a;}
if (isset($_GET['i']))
{
    $i=trim ( $_GET['i'] );
    $imgs['images']='R0lGODlhEAAQAPcAAP//////AP8A//8AAAD//wD/AAAA/wAAAPuBhP0RI7Crrr24vL25vn+CmJ6syZ6rxbfF4cbO3srO1oOk4WF4oo6fvpyuzqCvyqKxzKGwy6i30qa10KKxy6Oxy6Kwyb3M57G/2JyovqCswsTR6dDd9crW7NPe8tXg89Hb7uLs/uLr/Njh8eHp9wBe9YaZuYiauYeYtZWmw5mqxqCvyKi30KSyysXW8p+rvqu3ysPQ5tvo/eDr/dzm9uLs/OTt/Njg7eDn8+vy/gxn7Ku92LvO68TQ4uPu/t7p+c/Y5tXe7Nfg7cvb8ejw++vw9+fs87LD2OHu/lem/vH3/lSp/uTx/vP5/snj9+33/qGoqPz+/v3+/lXSYAC1AH3GdS6qHnDIW1OmL+Hp173JqoGaKby9srurRv3slf7dbf7cc/7Xb/7QZ/3SdP7FVd6wUP7LaP6/SP68SeS7cv6vMP62QNycN+CoTN2nTfvt1fueGf6qL/6vN/6xOf65V+C/jP2XEv6eGvmeH/6gI/6iJOCSKM+WR9OZSvjEff6ZG8mCKrB/RNifWv2EAPeAAf6IAuR5BP6JCP6QEMuMSNKTTeC7kbBhEKxdEL9rFqlfFa5iGMR0I45aJsuQVNikcNuugNG8pvLk1v17AO1zAKlUBq5XCL5kE7NiFqBdHplZHrVuLr97PcB/Q690PbF9T9CYY9KujN25l6NJAJtKA5JFBIZKF5NnQcqcdKaGbLVQAKpKAJA/ALGReKuQe+dXBa+ZjKuSh/BvO/55QcY0AN4dA+kwFdsTBv7+/v///wAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAACH5BAEAAMYALAAAAAAQABAAAAj3AI0JFJjkh0ElKHI8GMiQiJZiEItJMVGiBsOBWSIWq3ICCBOLDFFN4tTp1acsO4ogAfFiIKlNu3y5aqWqlpMlGiy4MHZL0h0rUaZMCCMmEasVHyBUMJYpkiIyQlpQ+LKlSypbNkbEsFRIYBwsDfp44QLG0KkIJGQgMmOnzpo0atyUGUOIzyUGOhyMaoPmDBs4c/TkAYbgl6gFUC4IrETnzZ48gvAMS0BMlgQjGQbmGiQn0CE/vIQF08QjBQeGsQD9gfSo0SJYvaj0wMBwFiVHjECFwkWLxREVtAcq8LQKUylTuppc8RFkw8UnOG6EEDGEhocZHWAEBAA7';
    $imgs['jpg']=&$imgs['images'];$imgs['gif']=&$imgs['images'];$imgs['png']=&$imgs['images'];$imgs['bmp']=&$imgs['images'];
    $imgs['htm']='R0lGODlhEAAQAPcAAFFRg1VVh1RVhldYiVpbi2lqm2BikGdpl25xnnN3o3yBq3l9p2JsnHN/sr3K/77K/3CAsrrK/8rY/8nX/sjW/bfM/8HQ9snY/8bV/MbV+7TM/7PF7KW54bDN/6K33oSj1q3N/5+02Cc8Woir3oep3KvN/6nO/5ulsjFcjyFDaSVDZbvO4+z1/ylrrD53sT9jiXSo3nOUtKHE5rrb+7/f/7TS8Mzl/4+gstPp/9vt//P5//3+/x1fmil6xC9yskGV5EZ3pShEXpS31qjG48Ti/9vq+OTy/+Hs9uz2/zCV7TKY7DqY6kKq/kiKxEqArkdriUpriHeauOjw9zKc7DGP2DSR2TGIzVCm5XCr2JatvyiW5T6p90im5kqItHCjyKDG4SWc5S6i61SVwFiWvCKR0ief5iWKwh5wnjCq8jyg2nzO+73X5nXH8oK10H6ux7bY6TK08ErB+W3E65jC1TKp3Tq57z667mzB5jzL/svq9pTp/Grz/////+fn593d3cfHx7GxsYaGhv///wAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAACH5BAEAAIIALAAAAAAQABAAAAjnAAUJJEGw4IgPDf4IXCiIxA4+ECM28AKIYUOIUta0afMGwoMfgPwsJDGmZEk+dhgUKKAgkMA5Y/TswVNHDJ87R3Q8WOASZh46ZsyUCeOEDxIWDhK4HNPHzRkZfHowqRKjiJEICAIxdQOHDJcrVJQwAbIiR4UDgZrGAcOjrdsXK3BoMBAoCxs0WnjIsMGX7xAbHQgE6pLGxZQkPmRYWfykBhEQAQbfONFiiZItcuQEETKDRgkAgdRE6dMERQoVIqCEsDBBggnQgr5ggUGBg4cNGDJckOBagEuBA4ILHx78t8BAyJMrRx4QADs=';
    $imgs['php']=&$imgs['htm'];$imgs['asp']=&$imgs['htm'];
    $imgs['zip']='R0lGODlhEAAQAOYAAMjY9gNKsCBarSZox7TP9iJesCdktitpuzp5xEiH0XCc05jC9ZCz3jl/0JC235S335a535i531qZ12+v7na09IKx3zeZ81Wm9Fur9mCz/1yl62e3/2Co62Ws7XG7/22x7ne+/3S38HW38HGx5nq88XSx5IjD84DA8oTD84vK+ZvP9YnJ963b+bzl+8fs/fT///79mf//r///uf//xPr2k//9pP/4hv/6kPr1kPr1kf/7mvfvgvPkbNm/Kdm/K/n25dm8Lvvwvfvzzt61APbNK9m3KPzUMP3VONm3MNu5Mtq6NN6+Ov3ZRv3aUPvdY/zkhfvnl/roodq0Kf7TN/zZV/zebPvec/zifPrjj/rprfrrs/juytKgBtSjB9WlCs2fC8+hDc6fDdWmD9KlEtWmFdSnGtKnHNGnHNKnHtKoHtiuJ9rDebN7DbJ8DbWBJKlrCqhrCrB5IKpxG/EYAP///wAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAACH5BAEAAHQALAAAAAAQABAAAAe7gHSCg3QoJyQhHx0cg2tgj2scLy4tLComIgmCX3OdYAOgoQ0YEgF0YVJFPT5ASElKS2x0FSWmaTAzMjE1Ojc2PG10DiimZjQ/W1lYVlRTQ8EQKaZnOUJaUFdOTEbPdNHT1dfZ290P0nRoOEFRT1VNR0TdESumYztqZWRiXl1c3QwjTLmJ8waOnFChFEzQYIoQHUkbMngAAUIDAoeDOBwwUKCAgIYYHXJgFDIjxZEjQToEAYDAAgoXLAQIBAA7';
    $imgs['rar']=&$imgs['zip'];$imgs['tar']=&$imgs['zip'];
    $imgs['dir']='R0lGODlhEAAOANUAAP//mf/3kf/0jv/rhf/mgP/gev/UbplmAJpnAZxpA55rBaBtB6NwCqVyDKh1D6t4Eq57FbB9F7OAGrSBG7WCHLeEHriFH7qHIbyJI72KJL+MJsCNJ8KPKcWSLMeULsmWMMuYMsyZM9OgOtypQ+azTe+8VvjFX//MZv///8nJycDAwLS0tH5+fnZ2dmJiYmFhYURERP///wAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAACH5BAEAADEALAAAAAAQAA4AAAaSwFgsBPp4hMikMIRqojjKJBNABaAylwpFEkGCAoJw2NmEsISfAaHDbm8wlolEEvMUOuQ8AcVgdQwhVYJUJgALLhwnIAABjY6NJQAKLxsnHQADmZqZJAAJLxknGgAFpaalIwAILxcnFgAGsbKxIgAHLilbERAPDg0MCwoJCAcwLTErLS/LzM0vLStCKSrU1dYqQkEAOw==';
    $imgs['<x>']='R0lGODlhDQAQANUAAL60urm203d3wXd3wMHB2sTG4ra5xL/K2MHO2b/N1bzM1MHP1r/O1b7N1LbKzrvN0L7Q0ZacnHh8fLq+vp6hoYqMjLu9vX1+fmRlZfz9/ez19MHIx/H49+n08uz29Ov18/f7+tS/SqGBObmXSZBqMIZoQZ2CXKmQbryqkZJ7YY14boRtbP///8fHx5eXl5OTk4mJiYODg3Z2dnBwcGlpaWBgYP///wAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAACH5BAEAADYALAAAAAANABAAAAZyQJtwSCyOQiRRKBVamVpCgkBAQqkCJ4DJJRwUSp2SpwQqsWbdR2ft4YAy5+5i3Wm/47YBo3PpW+5oeQl0dnCBAxB8fRcWLHgDDYRuhl0Hin6OhwiSgF0KfBaFjw5rHxobExQVMEMGERIYNTQyMS9Ft0RBADs=';
    $imgs['dots']='R0lGODlhAwABAJEAAAAAAP///2RkZP///yH5BAEAAAMALAAAAAADAAEAAAIC1FYAOw==';
    if(isset($imgs[$i]))$d=&$imgs[$i];else$d=&$imgs['<x>'];
    header('Content-type: image/gif');header('Content-Disposition: attachment; filename="'.$i.'"');print base64_decode($d);
}
else
{
    ob_start('ob_gzhandler');
    if(!$incl)print'<html><head><style type="text/css">'.$css.'</style></head><body>';
    $sb=isset($_GET['s'])?$_GET['s']:0;
    $so=isset($_GET['o'])?$_GET['o']:'asc';
    $st=$sb==='s'||$sb==='m'?SORT_NUMERIC:SORT_STRING;
    $d=isset($_GET['d'])?urldecode($_GET['d']):'';
    $d=$d!==''&&$d!=='/'?trim($d,'/').'/':'';
    $p=$root.$d;
    $h=@opendir($p);
    if(strstr($d,'../'))exit('Denied');
    if(!$h)exit('Unable to open "'.$d.'". <a href="'.$burl.'">Go home.</a>');
    $F=array();$D=array();
    while(FALSE!==($f=readdir($h)))if($f[0]!=='.')if(is_dir($p.$f))$D[]=array('n'=>$f,'m'=>filemtime($p.$f),'s'=>filesize($p.$f),'t'=>'Directory');else$F[]=array('n'=>$f,'m'=>filemtime($p.$f),'t'=>e($f),'s'=>filesize($p.$f));
    if($sb){$F=s($F,$sb,$so,$st);$D=s($D,$sb,$so,$st);}
    print '<table class="itable" cellspacing="2"><tr><td colspan="5">';
    print ($d!=='')?"<a href=\"$burl\"><strong>$name</strong></a> / ":"<strong>$name</strong> ";
    if($d!==''){$t=explode('/',trim($d,'/'));for($i=0,$r=array(),$z='';($r[]=@$t[$i]),$z=@$t[$i];$i++) { print(implode('/',$r)!==trim($d,'/'))? ('<a href="'.$burl.'d='.implode('%2F',$r).'"><strong>'.$z.'</strong></a> / ' ):"<strong>$z</strong>";}}
    $f=trim($d,'/');
    print '</td></tr><tr><td class="irows">&nbsp;</td>'
    .'<td class="irows"><a href="'.$burl.'s=n&amp;o='.($so=='asc'?'dsc':'asc').'&amp;d='.urlencode($f).'">Name</a></td>'
    .'<td class="irows"><a href="'.$burl.'s=s&amp;o='.($so=='asc'?'dsc':'asc').'&amp;d='.urlencode($f).'">Size</a></td>'
    .'<td class="irows"><a href="'.$burl.'s=t&amp;o='.($so=='asc'?'dsc':'asc').'&amp;d='.urlencode($f).'">Type</a></td>'
    .'<td class="irows" nowrap="nowrap"><a href="'.$burl.'s=m&amp;o='.($so=='asc'?'dsc':'asc').'&amp;d='.urlencode($f).'">Modified</a></td>';
    for($i=0,$c='';($c=@$D[$i++]);){print '<tr><td><img src="'.$surl.'?i=dir" alt="'.$c['n'].'"/></td><td><a href="'.$burl.'d='.urlencode($d.$c['n']).'">'.$c['n'].'</td><td>&nbsp;</td><td>&nbsp;</td><td>'.date('m/d/Y h:i A',$c['m']).'</td></tr>';}
    for($i=0,$c='';($c=@$F[$i++]);){print '<tr><td><img src="'.$surl.'?i='.$c['t'].'" alt="'.$c['n'].'"/></td><td><a href="'.$rurl.$d.$c['n'].'">'.$c['n'].'</td><td>'.number_format($c['s']/1024,1).'KB</td><td>'.$c['t'].'</td><td>'.date('m/d/Y h:i A',$c['m']).'</td></tr>';}
    print '<tr><td colspan="5" class="irows" style="text-align:center;background-position:top;">www.zhoudynasty.com</td></tr></table>';
    if(!$incl)print'</body></html>';
}
?>
<?php

}

?> 