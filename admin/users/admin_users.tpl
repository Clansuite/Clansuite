<h1>Users</h1>

    <form action="index.php" method="get">
        <input type="text" name="search" value="">
        <input type="submit" value="Search" onclick="alert('Under construction ..');return false;">
        <span class="description">searches within nick,email</span>
    </form>

<table class="listing">
    <tr>
        <th>Nick</th>
        <th>Email</th>
        <th>Level</th>
        <th>Joined</th>
        <th>Action</th>
    </tr>

<!-- BEGIN userslist -->
<div class="userslist">
  <a name="user-{user_id}" /></a>
  
    <tr>
        <td>{nick}</td>
        <td>{email}</td>
        <td>{level}</td>
        <td>{joined}</td>
        <td>
            <a href="{PHP_FILE}?action=edit&id={user_id}">edit</a> -
            <a href="{PHP_FILE}?action=delete&id={user_id}">delete</a>
        </td>
    </tr>
<!-- END userlist -->
    </table>
   <script type="text/javascript" src="../shared/listing.js"></script>
   <br />
