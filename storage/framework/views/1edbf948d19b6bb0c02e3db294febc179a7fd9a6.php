<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo e(mix('css/app.css')); ?>">
    <script src="<?php echo e(mix('js/app.js')); ?>" defer></script>

</head>

<body>

    <div class="container">
        <div class="row">
            <div class="col" id="app">
                <Library />
            </div>
        </div>
    </div>


</body>

</html>
<?php /**PATH D:\coding\Php\CancerMitrLaravel\crud-laravel-vue\resources\views/welcome.blade.php ENDPATH**/ ?>