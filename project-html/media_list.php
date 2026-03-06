<?php foreach ($mediaItems as $item): ?>
<div class="card">
    <img src="assets/<?php echo $item['image']; ?>" alt="<?php echo $item['title']; ?>">
    <h3><?php echo $item['title']; ?></h3>
    <p class="rating"><?php echo $item['rating']; ?> / 10</p>
</div>
<?php endforeach; ?>

