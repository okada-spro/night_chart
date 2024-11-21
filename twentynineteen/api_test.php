
<style>
.comment {
    border-bottom: 1px solid #ccc;
    padding: 10px 0;
}

.comment-author {
    font-weight: bold;
}

.comment-text {
    margin-top: 5px;
}

.content-item{
    display: block;
    font-size:16px;

    & span{
        font-weight: bold;
        color: red;
    }
}
</style>
<!-- <div class="video-container">
	<iframe class="video-player" src="https://www.youtube.com/embed/【動画ID】" allowfullscreen></iframe>
</div> -->

<?php
$apiKey = 'AIzaSyAflWEgU3pu-CF_0BDZDM2lo4Fm029Ayns';
$videoId = 'FkfYZ8CbzOI'; // 対象の動画ID

// 動画情報
$videoUrl = "https://www.googleapis.com/youtube/v3/videos?part=snippet&id={$videoId}&key={$apiKey}";
$videoResponse = file_get_contents($videoUrl);
$videoData = json_decode($videoResponse, true);
$movie_data = $videoData['items'][0]['snippet'];

$videoContentUrl = "https://www.googleapis.com/youtube/v3/videos?part=contentDetails&id={$videoId}&key={$apiKey}";
$videoContentResponse = file_get_contents($videoContentUrl);
$videoContentData = json_decode($videoContentResponse, true);
$movie_content_data = $videoContentData['items'][0]['contentDetails'];
echo "<pre>";
var_dump($movie_content_data);  //削除okd
echo "</pre>";

$movie_time = new DateInterval($movie_content_data['duration']);
var_dump($movie_time);  //削除okd
$hours = $movie_time->h;
$minutes = $movie_time->i;
$seconds = $movie_time->s;



$statistics_url = "https://www.googleapis.com/youtube/v3/videos?part=statistics&id={$videoId}&key={$apiKey}";
$statistics_response = file_get_contents($statistics_url);
$statisticsData = json_decode($statistics_response, true);

$statistics_data = $statisticsData['items'][0]['statistics'];


// echo "<pre>";
// var_dump($statisticsData);  //削除okd
// echo "</pre>";


$channelId = $videoData['items'][0]['snippet']['channelId'];

// チャンネル情報
$channelUrl = "https://www.googleapis.com/youtube/v3/channels?part=statistics&id={$channelId}&key={$apiKey}";
$channelResponse = file_get_contents($channelUrl);
$channelData = json_decode($channelResponse, true);
$channel_data = $channelData['items'][0]['statistics'];

$channelSnippetUrl = "https://www.googleapis.com/youtube/v3/channels?part=snippet&id={$channelId}&key={$apiKey}";
$channelSnippetResponse = file_get_contents($channelSnippetUrl);
$channelSnippetData = json_decode($channelSnippetResponse, true);
$channel_snippet_data = $channelSnippetData['items'][0]['snippet'];

// echo "<pre>";
// var_dump($channelData);  //削除okd
// echo "</pre>";


$play_no = $statistics_data['viewCount'];   //再生数
$total_play_no = $channel_data['viewCount'];    //チャンネル総再生数
$subscription_no = $channel_data['subscriberCount'];    //チャンネル登録者数
$comment_no = $statistics_data['commentCount']; //コメント数
$good_no = $statistics_data['likeCount'];   //高評価数

?>
    <div class="video-info">
    <div class="content-item">サムネ</div>
	<img class="video-thumbnail" src="<?php echo $movie_data['thumbnails']['default']['url'];?>" alt="動画サムネイル">
    

    <div class="content-item">動画タイトル: <span><?php echo $movie_data['title'];?></span></div>
    <div class="content-item">動画URL:     <span><?php echo "https://www.youtube.com/watch?v=".$videoData['items'][0]['id'];?></span></div>
    <div class="content-item">チャンネルID: <span><?php echo $movie_data['channelId'];?></span></div>
    <div class="content-item">チャンネル名: <span><?php echo $movie_data['channelTitle'];?></span></div>


    
    <div class="content-item">視聴回数:                <span class=""><?php echo $play_no; ?></span></div>
    <div class="content-item">拡散率(再生数/登録者数):  <span class=""><?php echo $statistics_data['viewCount']/$channel_data['subscriberCount']; ?></span></div>
    <div class="content-item">チャンネル登録者:         <span class=""><?php echo $subscription_no; ?></span></div>
    <div class="content-item">チャンネル総再生数:         <span class=""><?php echo $total_play_no; ?></span></div>
    <div class="content-item">チャンネル動画総投稿数:   <span class=""><?php echo $channel_data['videoCount']; ?></span></div>
    <div class="content-item">公開日:                   <span class=""><?php echo $movie_data['publishedAt']; ?></span></div>
    <div class="content-item">チャンネル開設日:         <span class=""><?php echo $channel_snippet_data['publishedAt']; ?></span></div>
    <div class="content-item">エンゲージ: <span class="">YouTube Analytics APIが必要</span></div>
    <div class="content-item">急上昇: <span class=""></span></div>
    <div class="content-item">動画の長さ:               <span class=""><?php echo sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds); ?></span></div>
    <div class="content-item">高評価数:                 <span class=""><?php echo $good_no; ?></span></div>
    <div class="content-item">favoriteCount:            <span class=""><?php echo $statistics_data['favoriteCount']; ?></span></div>
    <div class="content-item">高評価率:                 <span class=""><?php echo number_format(($good_no/$play_no)*100,2); ?>%</span></div>
    <div class="content-item">コメント数:               <span class=""><?php echo $comment_no; ?></span></div>
    <div class="content-item">コメント率:               <span class=""><?php echo number_format(($comment_no/$play_no)*100,2); ?>%</span></div>
    <div class="content-item">登録率（チャンネル動画総再生数/登録者数）: <span class=""><?php echo $total_play_no/ $subscription_no; ?></span></div>
    <div class="content-item">概要欄:                   <span><?php echo $movie_data['description'];?></span></div>
    <div class="content-item">タグ:                     <span><?php print_r($movie_data['tags']) ;?></span></div>
    <div class="content-item">デフォルト言語:           <span><?php echo $movie_data['defaultAudioLanguage'];?></span></div>
    


</div>