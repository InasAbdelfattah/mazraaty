importScripts('https://www.gstatic.com/firebasejs/4.10.1/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/4.10.1/firebase-messaging.js');

var config = {
    // apiKey: "AAAA8vC9dcc:APA91bHGtILjO8TtBCFEweYKUAz90PwlSMQZN-jaaEa-xjNcEHEJPuo1o8tNPSHTBxdVrffincJbBYv9kvCN-wQWx6dcD5657UG1fhS_VHRXgCitSVRYmKS72ksmiLjx6-RyiyvkV38J",
    // authDomain: "otlbha-project.firebaseapp.com",
    // databaseURL: "https://otlbha-project.firebaseio.com",
    // projectId: "otlbha-project",
    // storageBucket: "otlbha-project.appspot.com",
    messagingSenderId: "1043421033927"
};
firebase.initializeApp(config);
const messaging = firebase.messaging();

messaging.setBackgroundMessageHandler(function(payload) {
  console.log('[firebase-messaging-sw.js] Received background message ', payload);
  
  // Customize notification here
  
  
  const notificationTitle = payload.data.message;
  const notificationOptions = {
    body:  payload.data.description,
    icon: '/firebase-logo.png'
  };

  return self.registration.showNotification(notificationTitle,notificationOptions);
  
});







