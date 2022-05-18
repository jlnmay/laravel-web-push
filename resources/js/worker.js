console.log("Service Worker Loaded...");

self.addEventListener("push", (e) => {
    // console.log(e);
    const data = { title: "Title" };
    console.log("Push Received...");

    self.registration.showNotification(data.title, {
        body: "Notified by Traktel!",
        icon: "https://secureservercdn.net/72.167.25.126/sht.abe.myftpupload.com/wp-content/uploads/2018/10/TrakTel-Final-Logo_250x75.png?time=1643558506",
    });
});
