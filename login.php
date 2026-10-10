<?php
session_start();

$emailErr = $passwordErr = $error = "";
$email = $password = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $emailPattern = "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/";

    if (empty($email)) {
        $emailErr = "Please enter your email.";
    } elseif (!preg_match($emailPattern, $email)) {
        $emailErr = "Please enter a valid email address.";
    }

    if (empty($password)) {
        $passwordErr = "Please enter your password.";
    }

    if (empty($emailErr) && empty($passwordErr)) {
        $validEmail = "admin@ecocoins.ph";
        $validPassword = "admin123";

        $users = $_SESSION['ecoCoinsUsers'] ?? [];
        $isAuthenticated = false;
        $userName = "";

        if (strtolower($email) === strtolower($validEmail) && $password === $validPassword) {
            $isAuthenticated = true;
            $userName = "Admin";
        } else {
            foreach ($users as $user) {
                if (strtolower($user['email']) === strtolower($email) && $user['password'] === $password) {
                    $isAuthenticated = true;
                    $userName = $user['firstName'] . " " . $user['lastName'];
                    break;
                }
            }
        }

        if ($isAuthenticated) {
            $_SESSION['ecoCoinsCurrentUser'] = [
                'name' => $userName,
                'email' => $email
            ];
            header("Location: index.php");
            exit();
        } else {
            $error = "Invalid email or password.";
        }
    }
}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - EcoCoins</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
      .error-text {
        color: #dc3545;
        font-size: 0.85rem;
        margin-top: 0.25rem;
        display: block;
        font-weight: 500;
      }
    </style>
  </head>
  <body class="bg-light min-vh-100 d-flex flex-column justify-content-between">

    <nav class="navbar navbar-expand-lg navbar-dark bg-success border-bottom border-light">
      <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">EcoCoins</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav ms-auto">
            <li class="nav-item"><a class="nav-link" href="index.php#hero">Home</a></li>
            <li class="nav-item"><a class="nav-link" href="index.php#about">About</a></li>
            <li class="nav-item"><a class="nav-link" href="index.php#how-it-works">How It Works</a></li>
            <li class="nav-item"><a class="nav-link active fw-bold" href="login.php">Login</a></li>
            <li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>
          </ul>
        </div>
      </div>
    </nav>

    <main class="container py-5 my-auto">
      <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
          <div class="card border-success shadow-sm bg-white">
            <div class="card-header bg-success text-white text-center py-3">
              <h4 class="fw-bold mb-0">EcoCoins Account Login</h4>
            </div>
            <div class="card-body p-4">

              <?php if (!empty($error)): ?>
                <div class="alert alert-danger py-2 small"><?php echo htmlspecialchars($error); ?></div>
              <?php endif; ?>

              <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" novalidate>
                <div class="mb-3">
                  <label for="email" class="form-label text-dark fw-semibold">Email Address</label>
                  <input type="email" class="form-control border-success <?php echo !empty($emailErr) ? 'is-invalid' : ''; ?>" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" placeholder="name@example.com">
                  <?php if (!empty($emailErr)): ?>
                    <span class="error-text"><?php echo $emailErr; ?></span>
                  <?php endif; ?>
                </div>
                <div class="mb-3">
                  <label for="password" class="form-label text-dark fw-semibold">Password</label>
                  <input type="password" class="form-control border-success <?php echo !empty($passwordErr) ? 'is-invalid' : ''; ?>" id="password" name="password" placeholder="Enter your password">
                  <?php if (!empty($passwordErr)): ?>
                    <span class="error-text"><?php echo $passwordErr; ?></span>
                  <?php endif; ?>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-4">
                  <div class="form-check">
                    <input class="form-check-input border-success" type="checkbox" id="remember" name="remember">
                    <label class="form-check-label text-secondary small" for="remember">
                      Remember me
                    </label>
                  </div>
                  <a href="#" class="text-success small text-decoration-none">Forgot Password?</a>
                </div>
                <button type="submit" class="btn btn-success w-100 fw-bold py-2">Log In</button>
              </form>

            </div>
            <div class="card-footer bg-light border-top border-success text-center py-3">
              <span class="text-secondary small">Don't have an EcoCoins account yet? </span>
              <a href="register.php" class="text-success fw-bold text-decoration-none">Register Here</a>
            </div>
          </div>
          <div class="text-center mt-3">
            <a href="index.php" class="text-secondary small text-decoration-none">&larr; Back to Main Proposal</a>
          </div>
        </div>
      </div>
    </main>

    <footer class="bg-white border-top border-success text-center py-3">
      <div class="container">
        <p class="text-muted small mb-0">&copy; EcoCoins Project Proposal. Lipa City Malls Recycling Reward System.</p>
      </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>
