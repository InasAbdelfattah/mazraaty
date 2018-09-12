importScripts('https://www.gstatic.com/firebasejs/4.10.1/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/4.10.1/firebase-messaging.js');

var config = {
    apiKey: "AIzaSyDGAdzRX-G_A-Apr560xFKkUhftDC_y7so",
    authDomain: "otlbha-project.firebaseapp.com",
    databaseURL: "https://otlbha-project.firebaseio.com",
    projectId: "otlbha-project",
    storageBucket: "otlbha-project.appspot.com",
    messagingSenderId: "455185106819"
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







