<?php $this->include('header', ['b' => 'cccccc']); ?>

<p>Ciao <?php echo htmlentities($message); ?></p>

<?php if($a == 1): ?>
    <p>Ciao come stai?</p>
<?php else: ?> 
    <p>Noooo</p>
<?php endif; ?>