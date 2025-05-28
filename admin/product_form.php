<div class="form-group">
    <label for="name">Product Name</label>
    <input type="text" name="name" id="name" class="form-control"
        value="<?= isset($row['ProductName']) ? htmlspecialchars($row['ProductName']) : '' ?>" required>
</div>

<div class="form-group">
    <label for="desc">Description</label>
    <textarea name="desc" id="desc" class="form-control" required><?= isset($row['ProductDesc']) ? htmlspecialchars($row['ProductDesc']) : '' ?></textarea>
</div>

<div class="form-group">
    <label for="price">Price</label>
    <input type="text" name="price" id="price" class="form-control"
        value="<?= isset($row['ProductPrice']) ? htmlspecialchars($row['ProductPrice']) : '' ?>" required>
</div>

<div class="form-group">
    <label for="country">Country</label>
    <input type="text" name="country" id="country" class="form-control"
        value="<?= isset($row['ProductCountry']) ? htmlspecialchars($row['ProductCountry']) : '' ?>" required>
</div>

<div class="form-group">
    <label for="status">Status</label>
    <select name="status" id="status" class="form-control" required>
        <option value="1" <?= (isset($row['Status']) && $row['Status'] == 1) ? 'selected' : '' ?>>New</option>
        <option value="2" <?= (isset($row['Status']) && $row['Status'] == 2) ? 'selected' : '' ?>>Like New</option>
        <option value="3" <?= (isset($row['Status']) && $row['Status'] == 3) ? 'selected' : '' ?>>Used</option>
        <option value="4" <?= (isset($row['Status']) && $row['Status'] == 4) ? 'selected' : '' ?>>Very Old</option>
    </select>
</div>

<div class="form-group">
    <label for="category">Category</label>
    <select name="category" id="category" class="form-control" required>
        <?php
        $categories = GetItems('*', 'categories'); // You need to implement or already have this function
        foreach ($categories as $cat):
        ?>
            <option value="<?= $cat['CategoryID'] ?>" <?= (isset($row['CategoryID']) && $row['CategoryID'] == $cat['CategoryID']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($cat['CategoryName']) ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>

<?php if (!isset($row)): ?>
    <div class="form-group">
        <label for="members">User</label>
        <select name="members" id="members" class="form-control" required>
            <?php
            $users = GetItems('*', 'users'); // You need to implement or already have this function
            foreach ($users as $user):
            ?>
                <option value="<?= $user['UserID'] ?>"><?= htmlspecialchars($user['Username']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
<?php endif; ?>