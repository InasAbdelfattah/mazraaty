$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// Initialize Firebase
var config = {
    // apiKey: "AAAA8vC9dcc:APA91bHGtILjO8TtBCFEweYKUAz90PwlSMQZN-jaaEa-xjNcEHEJPuo1o8tNPSHTBxdVrffincJbBYv9kvCN-wQWx6dcD5657UG1fhS_VHRXgCitSVRYmKS72ksmiLjx6-RyiyvkV38J",
    // authDomain: "otlbha-project.firebaseapp.com",
    // databaseURL: "https://otlbha-project.firebaseio.com",
    // projectId: "otlbha-project",
    // storageBucket: "otlbha-project.appspot.com",
    messagingSenderId: "1043421033927"
};

firebase.initializeApp(config);


// navigator.serviceWorker.register('/firebase-messaging-sw.js')
//       .then(function (registration) {

//           messaging = firebase.messaging();

//           messaging.useServiceWorker(registration);
//           messaging.usePublicVapidKey("BPXd_w4pQhb_6R8kZ2d7vF3fALsy91R4w3cbKYPsh_bcfkcB9-mrsg5_efq7WSoaWt3iUSflQMYBnSoJ44d-8_s");
//          //request permission for Push Message.
//           messaging.requestPermission().then(function () {
//               console.log('grant');

//               messaging.getToken().then(function (currentToken) {
//                  console.log('current token', currentToken);
//             });
//           }).catch(function(error) {
//               console.log('Push Message is disallowed');
//           })
//       })


//   navigator.serviceWorker.register('/firebase-messaging-sw.js')
//     .then((registration) => {
//     messaging.useServiceWorker(registration);
//     });


const messaging = firebase.messaging();
//messaging.usePublicVapidKey("BPXd_w4pQhb_6R8kZ2d7vF3fALsy91R4w3cbKYPsh_bcfkcB9-mrsg5_efq7WSoaWt3iUSflQMYBnSoJ44d-8_s");
messaging.requestPermission()
    .then(function () {
        console.log('Notification permission granted.');
        getToken();

    })
    .catch(function (err) {
        console.log('Unable to get permission to notify.', err);
    });


function getToken() {
    // Callback fired if Instance ID token is updated.
    messaging.onTokenRefresh(function () {
        messaging.getToken().then(function (refreshedToken) {
            console.log(refreshedToken);
            Listen();
            // Indicate that the new Instance ID token has not yet been sent to the

        }).catch(function (err) {
            console.log('Unable to retrieve refreshed token ', err);
            showToken('Unable to retrieve refreshed token ', err);
        });
    });
    messaging.getToken()

        .then(function (currentToken) {
            if (currentToken) {
                console.log(currentToken);


                if (userId) {


                    $.ajax({
                        type: "POST",
                        url: url,
                        data: {id: userId, token: currentToken},


                        success: function (data) {

                        }
                    });
                }


                Listen();
            } else {
                // Show permission request.
                console.log('No Instance ID token available. Request permission to generate one.');

            }
        })
        .catch(function (err) {
            console.log('An error occurred while retrieving token. ', err);


        });

}

function Listen() {

    // messaging.setBackgroundMessageHandler(function(payload) {
    //     console.log('[firebase-messaging-sw.js] Received background message ', payload);
    //     const notificationTitle = 'Background Message from mazraty';
    //     const notificationOptions = {
    //        body: 'Background Message body.',
    //        icon: '/firebase-logo.png'
    //     };

    //     return self.registration.showNotification(notificationTitle,notificationOptions);
    // });

    messaging.onMessage(function (payload) {

        console.log(payload);
        // MyFetch("/CurrentData", {
        //     method: 'Get',
        //     credentials: "same-origin",
        //     headers: new Headers({
        //         'Content-Type': 'application/json'
        //     })
        // }).then(response => {
        //     if (response.notifications)
        //         $("#notificationDots").addClass("has-notifications");
        //     else
        //         $("#notificationDots").removeClass("has-notifications");
        //     $("#mainPic").attr("src", "/files/users/" + response.attachmentId)
        // }).catch(error => {
        //     debugger
        //     toastr["error"](translation.failed)
        //     if (error.console)
        //         console.log(error.console);
        // });
        // if (window.table && location.href.includes("NotificationList"))
        //     window.table.ajax.reload();

        // console.log('[firebase-messaging-sw.js] Received background message ', payload);
        //
        // // Customize notification here


        // const notificationTitle = payload.data.message;
        // const notificationOptions = {
        //     body: payload.data.description,
        //     icon: '/firebase-logo.png'
        // };

        return self.registration.showNotification("dasd", "asdasd");


    });


}

