/*jshint esversion: 6 */

var app = require("http").createServer();

// Se tiverem problemas com "same-origin policy" deverão activar o CORS.

// Aqui, temos um exemplo de código que ativa o CORS (alterar o url base)

// var app = require('http').createServer(function(req,res){
// Set CORS headers
//  res.setHeader('Access-Control-Allow-Origin', 'http://---your-base-url---');
//  res.setHeader('Access-Control-Request-Method', '*');
//  res.setHeader('Access-Control-Allow-Methods', 'UPGRADE, OPTIONS, GET');
//  res.setHeader('Access-Control-Allow-Credentials', true);
//  res.setHeader('Access-Control-Allow-Headers', req.header.origin);
//  if ( req.method === 'OPTIONS' || req.method === 'UPGRADE' ) {
//      res.writeHead(200);
//      res.end();
//      return;
//  }
// });

// NOTA: A solução correta depende da configuração do próprio servidor,
// e alguns casos do próprio browser.
// Assim sendo, não se garante que a solução anterior funcione.
// Caso não funcione é necessário procurar/investigar soluções alternativas

var io = require("socket.io")(app);

var LoggedUsers = require("./loggedusers.js");

app.listen(8080, function () {
  console.log("listening on *:8080");
});

let loggedUsers = new LoggedUsers();

io.on("connection", function (socket) {
  console.log("client has connected (socket ID = " + socket.id + ")");
  socket.on("msg_from_client", function (msg, userInfo) {
    if (userInfo === undefined) {
      io.sockets.emit("msg_from_server", 'User Unknown: "' + msg + '"');
    } else {
      io.sockets.emit("msg_from_server", userInfo.name + ': "' + msg + '"');
    }
  });
  socket.on("user_enter", function (user) {
    if (user !== undefined && user !== null) {
      if (user.type == "manager") {
        socket.join("manager");
        if (user.shift_active == 1) {
          socket.join("report");
        }
      }
      if (user.type == "cook") {
        socket.join("cook");
        socket.join("report");
      }
      if (user.type == "cashier") {
        socket.join("cashier");
        socket.join("report");
      }
      if (user.type == "waiter") {
        socket.join("waiter");
        socket.join("report");
      }
      loggedUsers.addUserInfo(user, socket.id);
    }
  });
  socket.on("user_exit", function (user) {
    if (user !== undefined && user !== null) {
      if (user.type == "manager") {
        socket.leave("manager");
        if (user.shift_active == 1) {
          socket.leave("report");
        }
      }
      if (user.type == "cook") {
        socket.leave("cook");
        socket.leave("report");
      }
      if (user.type == "cashier") {
        socket.leave("cashier");
        socket.leave("report");
      }
      if (user.type == "waiter") {
        socket.leave("waiter");
        socket.leave("report");
      }
      loggedUsers.removeUserInfoByID(user.id);
    }
  });
  socket.on("msg_from_worker_manager", function (msg, userInfo) {
    if (userInfo !== undefined || userInfo.type == "manager") {
      let channelName = "manager";
      io.sockets.to(channelName).emit("msg_from_server_manager", userInfo.name + ': "' + msg + '"');
    }
  });
  socket.on("msg_from_worker_report_manager", function (msg, userInfo) {
    if (userInfo !== undefined) {
      let channelName = "report";
      socket.broadcast.to(channelName).emit("msg_from_server_report", userInfo.name + "(" + userInfo.type + ")" + ': "' + msg + '"'
      );
      socket.broadcast.to(channelName).emit("notification_from_server_manager", {
        avatar: userInfo.photo_url,
        title: userInfo.name,
        subtitle: msg
      });
    }
  });
});