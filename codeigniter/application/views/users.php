<table>
    <th>User Name</th>
    <th>Name</th>
    <th>Email</th>
<?php foreach($people as $user):?>
    <tr>
        <td><?=$user['user_name']?></td>
        <td><?=$user['name']?></td>
        <td><?=$user['email']?></td>
    </tr>
<?php endforeach;?>
</table>
