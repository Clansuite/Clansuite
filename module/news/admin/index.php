<?php
require '../../shared/prepend.php';

$total_nr_news_comments = (int) "";

$newslist = $Db->getAll("SELECT n.news_id, n.news_title, n.news_category, n.news_added, n.news_hidden, 
u.nick , c.cat_name, c.cat_image_url FROM " . DB_PREFIX . "news n 
LEFT JOIN " . DB_PREFIX . "users u USING(user_id) 
LEFT JOIN " . DB_PREFIX . "category c ON ( n.news_category = c.cat_id AND c.cat_modulname = 'news')
ORDER BY n.news_id DESC");
foreach ($newslist as $k => $v) { $newslist[$k]['nr_comments'] = $Db->getOne("SELECT COUNT(*) FROM " . DB_PREFIX . "news_comments WHERE news_id = ?", $v['news_id']); 
$total_nr_news_comments = $newslist[$k]['nr_comments'] + $total_nr_news_comments;


}
?>

<?php $TITLE = 'News'; ?>
<?php include '../shared/header.tpl'; ?>

    <h1>News</h1>

    <p><input type="button" class="button" value="Add" onclick="location='add.php'">
    <?php echo 'Total News ('. count($newslist) .') & Comments ('.$total_nr_news_comments.')'; ?>
    </p>

    <table class="listing">
    <tr>
        <th>Id</th>
        <th>Added</th>
        <th>Title</th>
        <th>Category</th>
        <th>Cat-Image</th>
        <th>Author</th>
        <th>Draft (hidden)</th>       
        <th>Action</th>
    </tr>

    <?php foreach ($newslist as $news): ?>
    <tr>
        <td><?php echo $news['news_id']; ?></td>
        <td><?php echo substr($news['news_added'], 0, -3); ?></td>
        <td><?php echo $news['news_title']; ?></td>
        <td><?php echo $news['news_category'].' -> '.$news['cat_name']; ?></td>
        <td><img src="../<?php echo $news['cat_image_url']; ?>" 
                 border="0" alt="<?php echo $news['cat_image_url']; ?>" /></td>
        <td><?php echo $news['nick']; ?></td>
        <td><?php echo $news['news_hidden']; ?></td>
        <td>
         <p>
            <input type="button" class="button" value="Edit" onclick="location='edit.php?news_id=<?php echo $news['news_id']; ?>'">
            
            <input type="button" class="button" value="Delete" 
            onclick="if (confirm('Delete News #<?php echo $news['news_id'].' written by '.$news['nick'];?> and Comments ?')) 
            location = 'delete.php?news_id=<?php echo $news['news_id']; ?>';">
            
            <input type="button" class="button" value="<?php echo $news['nr_comments']; ?> Comments" onclick="location='add_news_comment.php?news_id=<?php echo $news['news_id']; ?>'">
            
            </p>
        </td>
    </tr>
    <?php endforeach; ?>

    </table>

    <script type="text/javascript" src="../shared/listing.js"></script>
    
<?php echo "<pre>",print_r($newslist),"</pre>";

include '../shared/footer.tpl'; ?>
