<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Demo Mail Template</title>
</head>
<body>
<p>Hi {{$data->fullName}} thanks for login, <br />
Your login token is {{$token}}
</p>
<table>
    <th>#</th>
    <th>Fullname</th>
    <th>Email</th>
    <th>Role</th>
    <th>DateTime</th>
    <tr>
        <td>{{$data->id}}</td>
        <td>{{$data->fullName}}</td>
        <td>{{$data->email}}</td>
        <td>{{$data->role->roleName}}</td>
        <td>{{$data->created_at}}</td>
    </tr>
</table>
</body>
</html>
