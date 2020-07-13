<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
		<meta name="description" content="" />
		<meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors" />
		<meta name="generator" content="Jekyll v4.0.1" />
		<title><?= $data['title'] ?></title>
		<link rel="canonical" href="https://getbootstrap.com/docs/4.5/examples/navbar-fixed/" />

		<link
			rel="stylesheet"
			href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
			integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk"
			crossorigin="anonymous"
		/>
		<script
			src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"
			integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI"
			crossorigin="anonymous"
		></script>

		<meta name="theme-color" content="#563d7c" />
	</head>
	<body>
        <main role="main" class="container my-5">
            <div class="jumbotron">
                <h1><?= $data['title'] ?></h1>
                <p class="lead"><?= $data['body'] ?></p>
				<h4>
				<?php
                    foreach ($data['tags'] as $tag) {
                        echo '<span class="badge badge-primary mx-1">' . $tag . '</span>';
                    }
                 ?>
				</h4>
            </div>
        </main>
	</body>
</html>
