
 <?php 
 $searchtitle=$_GET["title"];
 $searchtype=$_GET["title_type"];
 
 $url = "http://www.allmusic.com/search/".$searchtype."/".$searchtitle;
 
 $url = str_replace(" ", "+", $url); 
 $music_content = file_get_contents($url);
 $count = preg_match_all('/<tr class=\"search-result/',$music_content,$matches);
 $search_list = preg_split('/<tr class=\"search-result.+>/',$music_content);
 $xmlout="\n";
 $xmlout = $xmlout."<results>";

 if($count > 5)
 {
	$count = 5;
 }	
 if ($count > 0 )
 {
	 if ( $searchtype == 'artists')
	 {
		for($i=1;$i<$count+1;$i++)
		{
	
			$xmlout = $xmlout."<result ";
			preg_match('/<img src="http.+></',$search_list[$i],$pre_image);
			if(sizeof($pre_image) == 0)
			{
				
				$xmlout = $xmlout."cover=\"sprite-no-image.png\" ";
			
			}
			else
			{
				$image = preg_split('/src=|\sstyle/',$pre_image[0]);
				$xmlout = $xmlout."cover=".$image[1]." ";
			}	
			$count_name = preg_match('/<a href=.+\/a>/',$search_list[$i],$pre_name);
			if ( $count_name != 0 ) {
				$name = preg_split('/>|<\/a>/',$pre_name[0]); 
				$xmlout = $xmlout."name=\"".$name[1]."\" ";
			}	
			else {
				$name = "N/A";
				$xmlout = $xmlout."name=\"".$name."\" ";
			}	
			$pre_genre = preg_split('/<div class="info">/',$search_list[$i]);
			$genre = preg_split('/<br\/>/',$pre_genre[1]); 
			$trim_genre = trim($genre[0]);
			$trim_genre = str_replace(array('.', ' ', "\n", "\t", "\r", "\n\r"), '', $trim_genre);
			if( $trim_genre == '' )
			{
				$trim_genre = "N/A";
				$xmlout = $xmlout."genre=\"".$trim_genre."\" ";
			}
			else
			{

				$xmlout = $xmlout."genre=\"".$trim_genre."\" ";
			}
			
			$year = preg_split('/<\/div>/',$genre[1]);
			$trim_year = trim($year[0]);
			if ( $trim_year == '' )
			{
				$trim_year = "N/A";
				$xmlout = $xmlout."year=\"".$trim_year."\" ";
			}
			else
			{

				$xmlout = $xmlout."year=\"".$trim_year."\" ";
			}
			
			$count_details = preg_match('/<a href="http:\/\/www.allmusic.com\/artist\/.*/',$search_list[$i],$pre_details);
			if( $count_details != 0 ) {
				$details = preg_split('/<a href=|\s/',$pre_details[0]);
				$xmlout = $xmlout."details=".$details[1];
			}
			else {
				$details = "N/A";
				//echo "<td>".$details."</td>";
				$xmlout = $xmlout."details=".$details;
			}
			$xmlout = $xmlout."/>";
			
		}
			$xmlout = $xmlout."</results>";
			$xmlout = str_replace("&","&amp;",$xmlout);
			echo $xmlout;
	}

	else if( $searchtype == 'albums')
	{
		for($i=1;$i<$count+1;$i++)
		{
			$xmlout = $xmlout."<result ";
			preg_match('/<img src="http.+></',$search_list[$i],$pre_image_al);
			if(sizeof($pre_image_al) == 0)
			{
				
				$xmlout = $xmlout."cover=\"sprite-no-image_album.png\" ";
			
			}
			else
			{
				$image_al = preg_split('/src=|\sstyle/',$pre_image_al[0]);
		
				$xmlout = $xmlout."cover=".$image_al[1]." ";
			}	
			preg_match('/<a href=.+\/a>/',$search_list[$i],$pre_title);
			$title = preg_split('/>|<\/a>/',$pre_title[0]); 
			$xmlout = $xmlout."title=\"".$title[1]."\" ";
			$count_art = preg_match('/<a href="http:\/\/www.allmusic.com\/artist\/.*/',$search_list[$i],$pre_artist);
			
			if( $count_art != 0 )
			{
				$artist = preg_split('/">|<\/a>/',$pre_artist[0]);
				$size_art = sizeof($artist);
				$xmlout = $xmlout."artist=\"".$artist[1];
				if ( $size_art > 3 )
				{
					for ( $g= 3 ; $g < $size_art ; $g = $g+2 )
					{
					
						$xmlout = $xmlout.", ".$artist[$g];
					}
				}
				$xmlout = $xmlout."\" ";
			}
			else 
			{
				$artist = "N/A";
				$xmlout = $xmlout."artist=\"".$artist."\" ";
			}
			$pre_genre = preg_split('/<div class="info">/',$search_list[$i]);
			$genre = preg_split('/<br\/>/',$pre_genre[1]); 
			$post_genre = preg_split('/<\/div>/',$genre[1]);
			$trim_genre_al = trim( $post_genre[0] );
			$trim_genre_al = str_replace(array('.', ' ', "\n", "\t", "\r", "\n\r"), '', $trim_genre_al);
			if ( $trim_genre_al == '' )
			{
				$trim_genre_al = "N/A";
				$xmlout = $xmlout."genre=\"".$trim_genre_al."\" ";
			}	
			else
			{
				$xmlout = $xmlout."genre=\"".$trim_genre_al."\" ";
			}	
			$trim_year_al = trim( $genre[0] );
			$trim_year_al = str_replace(array('.', ' ', "\n", "\t", "\r", "\n\r"), '', $trim_year_al);
			if( $trim_year_al == '' )
			{
				$trim_year_al = "N/A";
				$xmlout = $xmlout."year=\"".$trim_year_al."\" ";
			}
			else
			{
				$xmlout = $xmlout."year=\"".$trim_year_al."\" ";
			}
			$count_link = preg_match('/<a href="http:\/\/www.allmusic.com\/album\/.*/',$search_list[$i],$pre_link_al);
			if( $count_link != 0)
			{
				$link_al = preg_split('/<a href=|\s/',$pre_link_al[0]);
				$xmlout = $xmlout."details=".$link_al[1];
				
			}
			else 
			{
				$link_al = "N/A";
				$xmlout = $xmlout."details=".$link_al;
			}
			//echo "</tr>";
			$xmlout = $xmlout."/>";
		}
		$xmlout = $xmlout."</results>";
		$xmlout = str_replace("&","&amp;",$xmlout);
		echo $xmlout;
	}	

	else if ( $searchtype == 'song' )
	{
		
		for($i=1;$i<$count+1;$i++)
		{	
			
			$xmlout = $xmlout."<result ";
			$count_play = preg_match_all('/<a href="http:\/\/rovimusic.*<\/a>/', $search_list[$i], $pre_play);
			
			if ( $count_play >0 )
			{
				$play = preg_split('/a href=|\s/', $pre_play[0][0]);
				$xmlout = $xmlout."sample=".$play[1]." ";
			}
			else
			{
				
				$xmlout = $xmlout."sample=\"sprite-no-image_song.png\" ";
			}
			$count_title = preg_match('/<a href="http:\/\/www.allmusic.com\/song\/.*<\/a>/', $search_list[$i], $pre_title);
			
			if( $count_title != 0 )
			{
				$title = preg_split('/&quot;/', $pre_title[0]);
				$xmlout = $xmlout."title=\"".$title[1]."\" ";
			}
			else
			{
				$title = "N/A";
				$xmlout = $xmlout."title=".$title." ";
			}
			$count_perform = preg_match( '/href="http:\/\/www.allmusic.com\/artist\/.*<\/a>/', $title[2], $pre_perform);
			
			if ( $count_perform != 0 )
			{
				$perform = preg_split('/">|<\/a>/', $pre_perform[0]);
				$size_per = sizeof($perform);
				$xmlout = $xmlout."performer=\"".$perform[1];
				if ( $size_per > 3 )
				{
					for ( $w= 3 ; $w < $size_per ; $w = $w+2 )
					{
		
						$xmlout = $xmlout.", ".$perform[$w];
					}
				}
				$xmlout = $xmlout."\" ";
			}
			else
				{
					$perform = "NA";
	
					$xmlout = $xmlout."performer=\"".$perform."\" ";
				}
			$count_comp = preg_match('/Composed by.*/', $search_list[$i], $pre_comp );
			if ( $count_comp != 0 )
			{
				$composer = preg_split('/">|<\/a>/' , $pre_comp[0]);
				$size_comp = sizeof($composer);
				
				$xmlout = $xmlout."composer=\"".$perform[1];
				if ( $size_comp > 3 )
				{
					for ( $j=3 ; $j<$size_comp; $j=$j+2)
					{ 
						
						$xmlout = $xmlout.", ".$composer[$j];
					}
				}
				
				$xmlout = $xmlout."\" ";
			}
			else 
			{
				$composer = "N/A";
				
				$xmlout = $xmlout."composer=\"".$composer."\" ";
			}
			$count_link_song = preg_match('/<a href="http:\/\/www.allmusic.com\/song\/.*<\/a>/', $search_list[$i], $pre_link_song);
			if( $count_link_song != 0 )
			{
				$link_song = preg_split('/href=|>/',$pre_link_song[0]);
				
				$xmlout = $xmlout."song=".$link_song[1];
			}
			else
			{
				$link_song = "N/A";
				$xmlout = $xmlout."song=".$link_song;
			}
			
			$xmlout = $xmlout."/>";
		}
		$xmlout = $xmlout."</results>";
		$xmlout = str_replace("&","&amp;",$xmlout);
		echo $xmlout;
	}
	//echo "</table>";
}	
else
{
	$xmlout = $xmlout."</results>";
	echo $xmlout;
} 
 
?>
 