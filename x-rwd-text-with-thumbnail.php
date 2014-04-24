<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
include_once(G5_LIB_PATH.'/thumbnail.lib.php');

widget_css();


$icon_url = widget_data_url( $widget_config['code'], 'icon' );

$file_headers = @get_headers($icon_url);

if($file_headers[0] == 'HTTP/1.1 404 Not Found') {
    $icon_url = x::url()."/widget/".$widget_config['name']."/img/icon.png";
}

if( $widget_config['forum1'] ) $_bo_table = $widget_config['forum1'];
else $_bo_table = $widget_config['default_forum_id'];

if( $widget_config['no'] ) $limit = $widget_config['no'];
else $limit = 5;

$list = g::posts( array(
			"bo_table" 	=>	$_bo_table,
			"limit"		=>	$limit,
			"select"	=>	"idx,domain,bo_table,wr_id,wr_parent,wr_is_comment,wr_comment,ca_name,wr_datetime,wr_hit,wr_good,wr_nogood,wr_name,mb_id,wr_subject,wr_content"
				)
		);	
		
		
$title = $widget_config['title'];
	if ( empty( $title ) ) {
		$cfg = g::config( $_bo_table, 'bo_subject' );
		$title = cut_str( $cfg['bo_subject'],10,"...");
	}

	if ( empty($title) ) {
		$title = "No title";
	}
?>

<div class="skin-update x-rwd-text-with-thumbnail">
    <div class="title">	
		<img class='icon' src='<?=$icon_url?>'>	
		<a href='<?=g::url_forum($_bo_table)?>'><?=$title?></a>
		
		<span class='more-button'><a href='<?=g::url_forum($_bo_table)?>'>자세히 ></a></span>
		<div style='clear:right;'></div>
	</div>
    <table>
	
    <?php
		$trs = array();
		$count_post = 0;
		for ($i=0; $i<count($list); $i++) {		
			//if ( $count_post >= $options['no'] ) break;
			$_wr_id = $list[$i]['wr_id'];
			$imgsrc = x::post_thumbnail( $_bo_table , $_wr_id, 40, 35 );
			if( empty($imgsrc) ) {
				$_wr_content = db::result("SELECT wr_content FROM $g5[write_prefix]$_bo_table WHERE wr_id='$_wr_id'");
				$image_from_tag = g::thumbnail_from_image_tag( $_wr_content, $_bo_table, 40, 35);
				if ( empty($image_from_tag) ) $img = "<img src='$widget_config[url]/img/no-image.png'/>";
				else $img = "<img src='$image_from_tag'/>";
			} else $img = "<img src='".$imgsrc['src']."'/>";			
			$count_post ++;
			ob_start();
	?>
	<tr valign='top'>
		
            <?php		
			
			
			echo "<td width='40'><div class='photo'><a href='".$list[$i]['url']."'>$img</a></div></td>";
			        
            echo "<td width='100%'>
					<div class='subject'><a href='".$list[$i]['url']."'>".cut_str($list[$i]['subject'], 15, '...')."</a></div>
					<div class='contents_wrapper'><a href='".$list[$i]['url']."'>".cut_str(strip_tags($list[$i]['wr_content']), 30, '...')."</a></div>
			
				</td>";
			
			if( !$list[$i]['wr_comment'] ) $comment_count = "<div class='comment_count no-comment'>0</div>";
			else $comment_count = "<div class='comment_count'>".strip_tags($list[$i]['wr_comment'])."</div>";
			$date_and_time = explode(" ",$list[$i]['wr_datetime']);
			if( $date_and_time[0] == date("Y-m-d") ) $post_date = $date_and_time[1];
			else $post_date = $date_and_time[0];

			echo "<td width='80'><div class='comment-time'>".$comment_count."<div class='time'>".$post_date."</div></div></td>";
             ?>	
	</tr>	
    <?php
			$trs[] = ob_get_clean();
		}
		echo implode( "<tr><td colspan='10'><div class='breaker'></div></td></tr>", $trs );
		?>
    <?php if(count($list) == 0) { //게시물이 없을 때  ?>
		<tr valign='top'>
			<td><div class='photo'><a href='http://www.philgo.net/bbs/board.php?bo_table=help&wr_id=5'><img src='<?=$widget_config['url']?>/img/no-image.png'/></a></div></td>
			 <td width='80%'>
				<div class='subject'><a href='http://www.philgo.net/bbs/board.php?bo_table=help&wr_id=5'>사이트 만들기 안내</a></div>
				<div class='contents_wrapper' style='margin-bottom: 8px;' ><a href='http://www.philgo.net/bbs/board.php?bo_table=help&wr_id=5'>사이트 만들기 안내</a></div>
			 </td>	
			<td><div class='comment-time'><div class='comment_count'>10</div><div class='time'><?=date('H:i', time())?></div></div></td>
		</tr valign='top'>
		<tr valign='top'>
			<td><div class='photo'><a href='http://www.philgo.net/bbs/board.php?bo_table=help&wr_id=4'><img src='<?=$widget_config['url']?>/img/no-image.png'/></a></div></td>
			 <td width='80%'>
				<div class='subject'><a href='http://www.philgo.net/bbs/board.php?bo_table=help&wr_id=4'>블로그 만들기</a></div>
				<div class='contents_wrapper' style='margin-bottom: 8px;'><a href='http://www.philgo.net/bbs/board.php?bo_table=help&wr_id=4'>블로그 만들기</a></div>
			</td>	
			<td><div class='comment-time'>0<br><span class='time'><?=date('H:i', time())?></span></div></td>
		</tr>
		<tr valign='top'>
			<td><div class='photo'><a href='http://www.philgo.net/bbs/board.php?bo_table=help&wr_id=3'><img src='<?=$widget_config['url']?>/img/no-image.png'/></a></div></td>
			 <td width='80%'>
				<div class='subject'><a href='http://www.philgo.net/bbs/board.php?bo_table=help&wr_id=3'>커뮤니티 사이트 만들기</a></div>
				<div class='contents_wrapper' style='margin-bottom: 8px;'><a href='http://www.philgo.net/bbs/board.php?bo_table=help&wr_id=3'>커뮤니티 사이트 만들기</a></div>
			</td>	
			<td><div class='comment-time'>0<br><span class='time'><?=date('H:i', time())?></span></div></td>
		</tr>
		<tr valign='top'>
			<td><div class='photo'><a href='http://www.philgo.net/bbs/board.php?bo_table=help&wr_id=2'><img src='<?=$widget_config['url']?>/img/no-image.png'/></a></div></td>
			 <td width='80%'>
				<div class='subject'><a href='http://www.philgo.net/bbs/board.php?bo_table=help&wr_id=2'>여행사 사이트 만들기</a></div>
				<div class='contents_wrapper' style='margin-bottom: 8px;'><a href='http://www.philgo.net/bbs/board.php?bo_table=help&wr_id=2'>여행사 사이트 만들기</a></div>
			</td>	
			<td><div class='comment-time'><div class='comment_count'>10</div><span class='time'><?=date('H:i', time())?></span></div></td>
		</tr>
		<tr valign='top'>
			<td><div class='photo'><a href='http://www.philgo.net/bbs/board.php?bo_table=help&wr_id=2'><img src='<?=$widget_config['url']?>/img/no-image.png'/></a></div></td>
			 <td width='80%'>
				<div class='subject'><a href='<?=url_site_config()?>'>사이트 설정하기</a></div>
				<div class='contents_wrapper' style='margin-bottom: 8px;'><a href='<?=url_site_config()?>'>사이트 설정 바로가기</a></div>
			</td>	
			<td><div class='comment-time'><div class='comment_count'>10</div><span class='time'><?=date('H:i', time())?></span></div></td>
		</tr>
    <?php
			
		}				
	?>
    </table>    
</div>