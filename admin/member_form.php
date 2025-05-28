<div class="form-group">
    <label for="usernameInput">User Name</label>
    <input type="text" required name="username" class="form-control" id="usernameInput"
        value="<?= isset($row['Username']) ? htmlspecialchars($row['Username']) : '' ?>" placeholder="Enter User Name">
</div>

<div class="form-group">
    <label for="passwordInput">Password</label>
    <?php if (isset($row['Password'])): ?>
        <input type="hidden" name="oldpassword" value="<?= $row['Password'] ?>">
    <?php endif; ?>
    <input type="password" name="newpassword" class="form-control" id="passwordInput" placeholder="Password">
    <i class="show-pass fa-eye fa-solid fa fa-2x"></i>
</div>

<div class="form-group">
    <label for="emailInput">Email address</label>
    <input type="email" required name="email" class="form-control" id="emailInput"
        value="<?= isset($row['Email']) ? htmlspecialchars($row['Email']) : '' ?>" placeholder="Enter email">
    <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
</div>

<div class="form-group">
    <label for="fullnameInput">Full Name</label>
    <input type="text" required name="fullname" class="form-control" id="fullnameInput"
        value="<?= isset($row['FullName']) ? htmlspecialchars($row['FullName']) : '' ?>" placeholder="Full Name">
</div>

<button type="submit" class="btn btn-primary">Submit</button>