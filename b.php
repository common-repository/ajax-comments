<?php
    //0.094u 0.008s 0:00.14 64.2%     2542+1272k 0+0io 0pf+0w
	$handle = @fopen("comments.php","r");
	$f=array();
	$end = array();
	if ($handle) 
	{
	    while (!feof($handle)) 
	    {
    		$buffer = fgets($handle, 4096);
    		$b = trim($buffer);
    		if (strlen($b)>0) $f[]=$buffer;
    		/* Here I check out if it only contains tabs, and spaces. 
        if not we save the original value. To have a nice DHTML code... ;) */
    		
    		if (preg_match('/foreach(.*?)(.*?)comments(.*?)as(.*?)comment(.*?):/',$buffer)) $s=count($f)-1;
    		if (preg_match('/(.*?)endforeach;(.*?)/',$buffer)) $end[]=count($f);
	    }
	    fclose($handle);
	}
	
//	$end = array('120','34','99','60','88','1');
	sort($end);
	
	foreach ($end as $k=>$v) if ($v<$s) unset($end[$k]);	     
	/* we unset every impossible value
    in this case the first value will be the correct endforeach :) */

	$e = array_shift($end);
	unset($end);
	/* We have the closest element, so we can free up the array. */
		
	for ($i = $s; $i < $e; $i ++)
	{
	    echo $f[$i]."\n";
	}
	
//	echo "sorszam: $s";
//	print_r($f[$s]);
//	print_r($end);
//    var_dump($f);
?>
