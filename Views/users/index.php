<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<h1>User List</h1>
<a href="/users/create">+ Add User</a>
<ul>
    <?php foreach ($users as $user): ?>
        <li>
            <a href="/users/<?php echo $user->id ?>">
                <?php echo $user->name ?> (<?php echo $user->email ?>)
            </a>
            <form action="/users/<?php echo $user->id ?>/delete" method="post" style="display:inline;">
                <button type="submit">Delete</button>
            </form>
        </li>
    <?php endforeach; ?>
</ul>
</body>
</html>