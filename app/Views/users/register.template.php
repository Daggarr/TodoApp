<?php require_once 'app/Views/partials/header.template.php'; ?>
<body>
<br>
    <form action="/" method="post">
        <div class="mb- w-50">
            <label for="username" class="form-label">Username:</label>
            <input type="text" class="form-control" id="username" name="username">
        </div>

        <div class="mb-3 w-50">
            <label for="Password" class="form-label">Password:</label>
            <input type="password" class="form-control" id="Password" name="password">
        </div>

        <button type="submit" class="btn btn-primary">Register</button>
    </form>
</body>
</html>

