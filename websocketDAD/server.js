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
      io.sockets
        .to(channelName)
        .emit("msg_from_server_manager", userInfo.name + ': "' + msg + '"');
    }
  });

  //US8
  socket.on("msg_from_worker_report_manager", function (msg, userInfo) {
    if (userInfo !== undefined) {
      let channelName = "report";
      socket.broadcast
        .to(channelName)
        .emit(
          "msg_from_server_report",
          userInfo.name + "(" + userInfo.type + ")" + ': "' + msg + '"'
        );
      socket.broadcast
        .to(channelName)
        .emit("notification_from_server_manager", {
          avatar: userInfo.photo_url,
          title: userInfo.name,
          subtitle: msg,
          actionRoute: "/privatedashboard/manager"
        });
    }
  });

  //Orders
  //US10 //US16
  socket.on("order_changed", function (changedOrder) {
    if (changedOrder.type === 1) {
      socket.broadcast.to("cook").emit("order_changed", {
        icon: "local_grocery_store",
        iconClass: "blue white--text",
        title: "Order",
        subtitle: "Order: " + changedOrder.order.id + " has changed to confirmed.",
        changedOrder: changedOrder.order,
        actionRoute: "/privatedashboard/cook"
      });

      socket.broadcast.to("manager").emit("order_changed", {
        icon: "local_grocery_store",
        iconClass: "blue white--text",
        title: "Order",
        subtitle: "Order: " + changedOrder.order.id + " has changed to confirmed.",
        changedOrder: changedOrder.order,
        actionRoute: "/privatedashboard/manager"
      });

      io.to("waiter").emit("order_changed", {
        icon: "local_grocery_store",
        iconClass: "blue white--text",
        title: "Order",
        subtitle: "Order: " + changedOrder.order.id + " has changed to confirmed.",
        changedOrder: changedOrder.order,
        actionRoute: "/privatedashboard/waiter"
      });
    } else if (changedOrder.type === 2) {
      socket.broadcast.to("cook").emit("order_changed", {
        icon: "local_grocery_store",
        iconClass: "blue white--text",
        title: "Order",
        subtitle: "Order: " + changedOrder.order.id + " in preparation.",
        changedOrder: changedOrder.order,
        actionRoute: "/privatedashboard/cook"
      });

      socket.broadcast.to("manager").emit("order_changed", {
        icon: "local_grocery_store",
        iconClass: "blue white--text",
        title: "Order",
        subtitle: "Order: " + changedOrder.order.id + " in preparation.",
        changedOrder: changedOrder.order,
        actionRoute: "/privatedashboard/manager"
      });

      io.to("waiter").emit("order_changed", {
        icon: "local_grocery_store",
        iconClass: "blue white--text",
        title: "Order",
        subtitle: "Order: " + changedOrder.order.id + " in preparation.",
        changedOrder: changedOrder.order,
        actionRoute: "/privatedashboard/waiter"
      });

    } else if (changedOrder.type === 3) {
      socket.broadcast.to("cook").emit("order_changed", {
        icon: "local_grocery_store",
        iconClass: "blue white--text",
        title: "Order",
        subtitle: "Order: " + changedOrder.order.id + " prepared.",
        changedOrder: changedOrder.order,
        actionRoute: "/privatedashboard/cook"
      });

      socket.broadcast.to("manager").emit("order_changed", {
        icon: "local_grocery_store",
        iconClass: "blue white--text",
        title: "Order",
        subtitle: "Order: " + changedOrder.order.id + " prepared.",
        changedOrder: changedOrder.order,
        actionRoute: "/privatedashboard/manager"
      });

      io.to("waiter").emit("order_changed", {
        icon: "local_grocery_store",
        iconClass: "blue white--text",
        title: "Order",
        subtitle: "Order: " + changedOrder.order.id + " prepared.",
        changedOrder: changedOrder.order,
        actionRoute: "/privatedashboard/waiter"
      });
    } else if (changedOrder.type === 4) {
      socket.broadcast.to("cook").emit("order_changed", {
        icon: "local_grocery_store",
        iconClass: "blue white--text",
        title: "Order",
        subtitle: "Order: " + changedOrder.order.id + " delivered.",
        changedOrder: changedOrder.order,
        actionRoute: "/privatedashboard/cook"
      });

      socket.broadcast.to("manager").emit("order_changed", {
        icon: "local_grocery_store",
        iconClass: "blue white--text",
        title: "Order",
        subtitle: "Order: " + changedOrder.order.id + " delivered.",
        changedOrder: changedOrder.order,
        actionRoute: "/privatedashboard/manager"
      });

      io.to("waiter").emit("order_changed", {
        icon: "local_grocery_store",
        iconClass: "blue white--text",
        title: "Order",
        subtitle: "Order: " + changedOrder.order.id + " delivered.",
        changedOrder: changedOrder.order,
        actionRoute: "/privatedashboard/waiter"
      });

    } else if (changedOrder.type === 5) {
      socket.broadcast.to("cook").emit("order_changed", {
        icon: "local_grocery_store",
        iconClass: "blue white--text",
        title: "Order",
        subtitle: "Order: " + changedOrder.order.id + " not delivered.",
        changedOrder: changedOrder.order,
        actionRoute: "/privatedashboard/cook"
      });

      socket.broadcast.to("manager").emit("order_changed", {
        icon: "local_grocery_store",
        iconClass: "blue white--text",
        title: "Order",
        subtitle: "Order: " + changedOrder.order.id + " not delivered.",
        changedOrder: changedOrder.order,
        actionRoute: "/privatedashboard/manager"
      });

      io.to("waiter").emit("order_changed", {
        icon: "local_grocery_store",
        iconClass: "blue white--text",
        title: "Order",
        subtitle: "Order: " + changedOrder.order.id + " not delivered.",
        changedOrder: changedOrder.order,
        actionRoute: "/privatedashboard/waiter"
      });
    } else {
      socket.broadcast.to("cook").emit("order_changed", {
        icon: "local_grocery_store",
        iconClass: "blue white--text",
        title: "Order",
        subtitle: "Order: " + changedOrder.id + " changed.",
        changedOrder: changedOrder,
        actionRoute: "/privatedashboard/cook"
      });

      socket.broadcast.to("manager").emit("order_changed", {
        icon: "local_grocery_store",
        iconClass: "blue white--text",
        title: "Order",
        subtitle: "Order: " + changedOrder.id + " changed.",
        changedOrder: changedOrder,
        actionRoute: "/privatedashboard/manager"
      });

      io.to("waiter").emit("order_changed", {
        icon: "local_grocery_store",
        iconClass: "blue white--text",
        title: "Order",
        subtitle: "Order: " + changedOrder.id + " changed.",
        changedOrder: changedOrder,
        actionRoute: "/privatedashboard/waiter"
      });
    }
  });

  socket.on("order_created", function (changedOrder) {
    socket.broadcast.to("cook").emit("order_created", {
      icon: "local_grocery_store",
      iconClass: "green white--text",
      title: "Order",
      subtitle: "Order: " + changedOrder.id + " created.",
      changedOrder: changedOrder,
      actionRoute: "/privatedashboard/cook"
    });

    socket.broadcast.to("manager").emit("order_created", {
      icon: "local_grocery_store",
      iconClass: "green white--text",
      title: "Order",
      subtitle: "Order: " + changedOrder.id + " created.",
      changedOrder: changedOrder,
      actionRoute: "/privatedashboard/manager"
    });

    socket.broadcast.to("waiter").emit("order_created", {
      icon: "local_grocery_store",
      iconClass: "green white--text",
      title: "Order",
      subtitle: "Order: " + changedOrder.id + " created.",
      changedOrder: changedOrder,
      actionRoute: "/privatedashboard/waiter"
    });
  });

  socket.on("order_deleted", function (changedOrder) {
    socket.broadcast.to("cook").emit("order_deleted", {
      icon: "local_grocery_store",
      iconClass: "red white--text",
      title: "Order",
      subtitle: "Order: " + changedOrder.id + " deleted.",
      changedOrder: changedOrder,
      actionRoute: "/privatedashboard/cook"
    });

    socket.broadcast.to("manager").emit("order_deleted", {
      icon: "local_grocery_store",
      iconClass: "red white--text",
      title: "Order",
      subtitle: "Order: " + changedOrder.id + " deleted.",
      changedOrder: changedOrder,
      actionRoute: "/privatedashboard/manager"
    });

    socket.broadcast.to("waiter").emit("order_deleted", {
      icon: "local_grocery_store",
      iconClass: "red white--text",
      title: "Order",
      subtitle: "Order: " + changedOrder.id + " deleted.",
      changedOrder: changedOrder,
      actionRoute: "/privatedashboard/waiter"
    });
  });

  //US15 5sec

  //Invoices
  //US23
  socket.on("invoice_changed", function (changedInvoice) {
    socket.broadcast.to("manager").emit("invoice_changed", {
      icon: "receipt",
      iconClass: "blue white--text",
      title: "Invoice",
      subtitle: "Invoice: " + changedInvoice.id + " changed.",
      changedInvoice: changedInvoice,
      actionRoute: "/privatedashboard/manager"
    });

    socket.broadcast.to("cashier").emit("invoice_changed", {
      icon: "receipt",
      iconClass: "blue white--text",
      title: "Invoice",
      subtitle: "Invoice: " + changedInvoice.id + " changed.",
      changedInvoice: changedInvoice,
      actionRoute: "/privatedashboard/cashier"
    });
  });

  socket.on("invoice_created", function (changedInvoice) {
    socket.broadcast.to("manager").emit("invoice_created", {
      icon: "receipt",
      iconClass: "green white--text",
      title: "Invoice",
      subtitle: "Invoice: " + changedInvoice.id + " created.",
      changedInvoice: changedInvoice,
      actionRoute: "/privatedashboard/manager"
    });

    socket.broadcast.to("cashier").emit("invoice_created", {
      icon: "receipt",
      iconClass: "green white--text",
      title: "Invoice",
      subtitle: "Invoice: " + changedInvoice.id + " created.",
      changedInvoice: changedInvoice,
      actionRoute: "/privatedashboard/cashier"
    });
  });

  socket.on("invoice_deleted", function (changedInvoice) {
    socket.broadcast.to("manager").emit("invoice_deleted", {
      icon: "receipt",
      iconClass: "green white--text",
      title: "Invoice",
      subtitle: "Invoice: " + changedInvoice.id + " deleted.",
      changedInvoice: changedInvoice,
      actionRoute: "/privatedashboard/manager"
    });

    socket.broadcast.to("cashier").emit("invoice_deleted", {
      icon: "receipt",
      iconClass: "green white--text",
      title: "Invoice",
      subtitle: "Invoice: " + changedInvoice.id + " deleted.",
      changedInvoice: changedInvoice,
      actionRoute: "/privatedashboard/cashier"
    });
  });

  //Meals
  //US33 -> invoice change to not paid and meal to so notificaton must be send
  socket.on("meal_changed", function (changedMeal) {
    socket.broadcast.to("manager").emit("meal_changed", {
      icon: "restaurant",
      iconClass: "blue white--text",
      title: "Meal",
      subtitle: "Meal changed on Table: " + changedMeal.table_number,
      changedMeal: changedMeal,
      actionRoute: "/privatedashboard/manager"
    });

    socket.broadcast.to("waiter").emit("meal_changed", {
      icon: "restaurant",
      iconClass: "blue white--text",
      title: "Meal",
      subtitle: "Meal changed on Table: " + changedMeal.table_number,
      changedMeal: changedMeal,
      actionRoute: "/privatedashboard/waiter"
    });

    socket.broadcast.to("cashier").emit("meal_changed", {
      icon: "restaurant",
      iconClass: "blue white--text",
      title: "Meal",
      subtitle: "Meal changed on Table: " + changedMeal.table_number,
      changedMeal: changedMeal,
      actionRoute: "/privatedashboard/cashier"
    });
  });

  socket.on("meal_created", function (changedMeal) {
    socket.broadcast.to("manager").emit("meal_created", {
      icon: "restaurant",
      iconClass: "green white--text",
      title: "Meal",
      subtitle: "Meal created on Table: " + changedMeal.table_number,
      changedMeal: changedMeal,
      actionRoute: "/privatedashboard/manager"
    });

    socket.broadcast.to("waiter").emit("meal_created", {
      icon: "restaurant",
      iconClass: "green white--text",
      title: "Meal",
      subtitle: "Meal created on Table: " + changedMeal.table_number,
      changedMeal: changedMeal,
      actionRoute: "/privatedashboard/waiter"
    });

    socket.broadcast.to("cashier").emit("meal_created", {
      icon: "restaurant",
      iconClass: "green white--text",
      title: "Meal",
      subtitle: "Meal created on Table: " + changedMeal.table_number,
      changedMeal: changedMeal,
      actionRoute: "/privatedashboard/cashier"
    });
  });

  socket.on("meal_deleted", function (changedMeal) {
    socket.broadcast.to("manager").emit("meal_deleted", {
      icon: "restaurant",
      iconClass: "red white--text",
      title: "Meal",
      subtitle: "Meal deleted on Table: " + changedMeal.table_number,
      changedMeal: changedMeal,
      actionRoute: "/privatedashboard/manager"
    });

    socket.broadcast.to("waiter").emit("meal_deleted", {
      icon: "restaurant",
      iconClass: "red white--text",
      title: "Meal",
      subtitle: "Meal deleted on Table: " + changedMeal.table_number,
      changedMeal: changedMeal,
      actionRoute: "/privatedashboard/waiter"
    });

    socket.broadcast.to("cashier").emit("meal_deleted", {
      icon: "restaurant",
      iconClass: "red white--text",
      title: "Meal",
      subtitle: "Meal deleted on Table: " + changedMeal.table_number,
      changedMeal: changedMeal,
      actionRoute: "/privatedashboard/cashier"
    });
  });

  //tables
  socket.on("table_created", function (changedTable) {
    socket.broadcast.to("manager").emit("table_created", {
      icon: "airline_seat_recline_normal",
      iconClass: "green white--text",
      title: "Table",
      subtitle: "Table: " + changedTable.table_number + " created.",
      changedTable: changedTable,
      actionRoute: "/privatedashboard/manager"
    });

    socket.broadcast.to("waiter").emit("table_created", {
      icon: "airline_seat_recline_normal",
      iconClass: "green white--text",
      title: "Table",
      subtitle: "Table: " + changedTable.table_number + " created.",
      changedTable: changedTable,
      actionRoute: "/privatedashboard/waiter"
    });
  });

  socket.on("table_changed", function (changedTable) {
    socket.broadcast.to("manager").emit("table_changed", {
      icon: "airline_seat_recline_normal",
      iconClass: "blue white--text",
      title: "Table",
      subtitle: "Table: " + changedTable.table_number + " changed.",
      changedTable: changedTable,
      actionRoute: "/privatedashboard/manager"
    });

    socket.broadcast.to("waiter").emit("table_changed", {
      icon: "airline_seat_recline_normal",
      iconClass: "blue white--text",
      title: "Table",
      subtitle: "Table: " + changedTable.table_number + " changed.",
      changedTable: changedTable,
      actionRoute: "/privatedashboard/waiter"
    });
  });

  socket.on("table_deleted", function (changedTable) {
    socket.broadcast.to("manager").emit("table_deleted", {
      icon: "airline_seat_recline_normal",
      iconClass: "red white--text",
      title: "Table",
      subtitle: "Table: " + changedTable.table_number + " deleted.",
      changedTable: changedTable,
      actionRoute: "/privatedashboard/manager"
    });

    socket.broadcast.to("waiter").emit("table_deleted", {
      icon: "airline_seat_recline_normal",
      iconClass: "red white--text",
      title: "Table",
      subtitle: "Table: " + changedTable.table_number + " deleted.",
      changedTable: changedTable,
      actionRoute: "/privatedashboard/waiter"
    });
  });

  //Items
  socket.on("item_created", function (changedItem) {
    socket.broadcast.emit("item_created", {
      icon: "fastfood",
      iconClass: "green white--text",
      title: "Item",
      subtitle: "Item: " + changedItem.name + " created.",
      changedItem: changedItem,
      actionRoute: "/items"
    });
  });

  socket.on("item_changed", function (changedItem) {
    socket.broadcast.emit("item_changed", {
      icon: "fastfood",
      iconClass: "blue white--text",
      title: "Item",
      subtitle: "Item: " + changedItem.name + " changed.",
      changedItem: changedItem,
      actionRoute: "/items"
    });
  });

  socket.on("item_deleted", function (changedItem) {
    socket.broadcast.emit("item_deleted", {
      icon: "fastfood",
      iconClass: "red white--text",
      title: "Item",
      subtitle: "Item: " + changedItem.name + " deleted.",
      changedItem: changedItem,
      actionRoute: "/items"
    });
  });

  //Users
  socket.on("user_created", function (changedUser) {
    socket.broadcast.emit("user_created", changedUser);
  });

  socket.on("user_deleted", function (changedUser) {
    socket.broadcast.emit("user_deleted", changedUser);
  });

  socket.on("user_changed", function (changedUser) {
    socket.broadcast.emit("user_changed", changedUser);
  });

  socket.on("user_blocked", function (changedUser) {
    socket.broadcast.emit("user_blocked", changedUser);
  });

  socket.on("user_unblocked", function (changedUser) {
    socket.broadcast.emit("user_unblocked", changedUser);
  });
});