<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="syle.css">
    <title>Document</title>
</head>

<body>
    <div class="etoileAvis">
        <label class="container" id="etoile1">
            <input type="checkbox">
            <img class="star-img" src="../siteLinks/asset/icons/blanc/star.svg" width="40px">
        </label>

        <label class="container" id="etoile2">
            <input type="checkbox">
            <img class="star-img" src="../siteLinks/asset/icons/blanc/star.svg" width="40px">
        </label>

        <label class="container" id="etoile3">
            <input type="checkbox">
            <img class="star-img" src="../siteLinks/asset/icons/blanc/star.svg" width="40px">
        </label>

        <label class="container" id="etoile4">
            <input type="checkbox">
            <img class="star-img" src="../siteLinks/asset/icons/blanc/star.svg" width="40px">
        </label>

        <label class="container" id="etoile5">
            <input type="checkbox">
            <img class="star-img" src="../siteLinks/asset/icons/blanc/star.svg" width="40px">
        </label>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const checkboxes = document.querySelectorAll('.container input');

            checkboxes.forEach(function (checkbox, index) {
                checkbox.addEventListener('change', function () {
                    const starImg = this.parentElement.querySelector('.star-img');
                    if (this.checked) {
                        // Si la case est cochée, cocher les cases précédentes
                        for (let i = 0; i <= index; i++) {
                            checkboxes[i].checked = true;
                            checkboxes[i].parentElement.querySelector('.star-img').src = '../siteLinks/asset/icons/bleu/star.svg';
                        }
                    } else {
                        // Si la case est décochée, décocher les cases suivantes
                        for (let i = checkboxes.length - 1; i > index; i--) {
                            checkboxes[i].checked = false;
                            checkboxes[i].parentElement.querySelector('.star-img').src = '../siteLinks/asset/icons/blanc/star.svg';
                        }
                    }
                });
            });
        });
    </script>

</body>

</html>
