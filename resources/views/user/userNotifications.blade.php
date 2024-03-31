@extends('layouts.userMaster')
@section('content')

<h3>Notifications</h3>

<table class="table">
    <thead>
    <tr>
        <th scope="col">#</th>
        <th scope="col">Name</th>
        <th scope="col">content</th>
        <th scope="col">Actions</th>
    </tr>
    </thead>
    <tbody>
    @foreach($notifications as $notification)
        <tr>
            <th scope="row">{{$notification->id}}</th>
            <td>{{$notification->name}}</td>
            <td>{{$notification->content}}</td>
            <td>
            @if($notification->status == 0)
            <a href="{{url('users/readNotifications/'.$jwt_token.'/'.$notification->id)}}" type="button" class="btn btn-success" >{{__('Mark As Read')}}</a>
            @endif
            <a href="{{url('users/deleteNotifications/'.$jwt_token.'/'.$notification->id)}}" type="button" class="btn btn-danger" >{{__('Delete')}}</a>
            </td>


        </tr>
    @endforeach
    </tbody>
</table>
@if(Session::has('success'))
    <div class="alert alert-success" role="alert">

        {{Session::get('success')}}

    </div>
@endif

@if(Session::has('error'))
    <div class="alert alert-danger" role="alert">

        {{Session::get('error')}}

    </div>
@endif
<script type="text/javascript">

    // Your web app's Firebase configuration
    var firebaseConfig = {
        apiKey: "AIzaSyCfB2ufYqnelcTDrwWCL2G65VxuNZL2vCY",
        authDomain: "reservation-b4d33.firebaseapp.com",
        projectId: "reservation-b4d33",
        storageBucket: "reservation-b4d33.appspot.com",
        messagingSenderId: "318547199077",
        appId: "1:318547199077:web:5abc3bc8f40233a4e7328c",
        measurementId: "G-FL8B8TNP4S"
    };
    // Initialize Firebase
    firebase.initializeApp(firebaseConfig);
    //firebase.analytics();
    const messaging = firebase.messaging();
    messaging
        .requestPermission()
        .then(function () {
//MsgElem.innerHTML = "Notification permission granted."
            console.log("Notification permission granted.");

            // get the token in the form of promise
            return messaging.getToken()
        })
        .then(function(token) {
            // print the token on the HTML page
            console.log(token);



        })
        .catch(function (err) {
            console.log("Unable to get permission to notify.", err);
        });

    messaging.onMessage(function(payload) {
        console.log(payload);
        var notify;
        notify = new Notification(payload.notification.title,{
            body: payload.notification.body,
            icon: payload.notification.icon,
            tag: "Dummy"
        });
        console.log(payload.notification);
    });

    //firebase.initializeApp(config);
    var database = firebase.database().ref().child("/users/");

    database.on('value', function(snapshot) {
        renderUI(snapshot.val());
    });

    // On child added to db
    database.on('child_added', function(data) {
        console.log("Comming");
        if(Notification.permission!=='default'){
            var notify;

            notify= new Notification('CodeWife - '+data.val().username,{
                'body': data.val().message,
                'icon': 'bell.png',
                'tag': data.getKey()
            });
            notify.onclick = function(){
                alert(this.tag);
            }
        }else{
            alert('Please allow the notification first');
        }
    });

    self.addEventListener('notificationclick', function(event) {
        event.notification.close();
    });

</script>

@endsection

