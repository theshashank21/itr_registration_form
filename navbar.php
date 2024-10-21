<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel='href' stylesheet='style.css'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</head>

<body>
    <nav class="navbar navbar-expand-lg bg-dark" style="margin-top: 0px; padding: 6px" data-bs-theme="dark">
        <div class="container align-middle">
            <a class="navbar-brand" style="font-size: 24px" href="index.php">ITR Form</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" style="right: 50px; position: absolute; font-size: 18px" id="navbarTogglerDemo02">
                <ul class="navbar-nav me-0 mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" id="listingLink" href="index.php">Home</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" id="listingLink" href="listing.php">Listing</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <script>
        let home = document.getElementById('homeLink');
        let listing = document.getElementById('listingLink');
        let searching = document.getElementById('searchingLink');
        console.log(home)
        home.addEventListener("click", function(e) {
            e.preventDefault();

            listing.classList.remove('active');
            searching.classList.remove('active');
            home.classList.add('active');
            window.location.href = 'index.php';
        });
        listing.addEventListener("click", function(e) {
            e.preventDefault();
            window.location.href = 'listing.php';
            listing.classList.add('active');
            searching.classList.remove('active');
            home.classList.remove('active');
        });
        searching.addEventListener("click", function(e) {
            e.preventDefault();
            window.location.href = 'searching.php';
            listing.classList.remove('active');
            searching.classList.add('active');
            home.classList.remove('active');
        });
    </script>
</body>

</html>