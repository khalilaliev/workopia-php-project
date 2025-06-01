<?= load_partial('head'); ?>
<?= load_partial('header'); ?>

<section>
  <div class="container text-center mx-auto p-4 my-6">
    <div class="text-center text-3xl mb-4 font-bold border border-gray-300 p-3"><?= $status ?></div>
    <p class="text-center text-2xl mb-4">
      <?= $message ?>
    </p>
    <a class=" bg-yellow-500 hover:bg-yellow-600 text-black px-4 py-2 rounded hover:shadow-md transition duration-300" href="/">Go back home</a>
  </div>
</section>


<?= load_partial('footer'); ?>