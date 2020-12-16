import Login from "@/views/Login.vue";
import Dashboard from "@/views/Dashboard.vue";
import Products from "@/views/Products.vue";
import Product from "@/views/Product.vue";
import Home from "@/views/Home.vue";
import Orders from "@/views/Orders.vue";
import Order from "@/views/Order.vue";
import store from "@/store/store";

export default [
  {
    path: "/",
    component: Home,
  },
  {
    path: "/dashboard",
    name: "Dashboard",
    component: Dashboard,
    meta: { requiresAuth: true },
  },
  {
    path: "/products",
    name: "Products",
    component: Products,
    meta: { requiresAuth: true },
  },
  {
    path: "/login",
    component: Login,
    beforeEnter: (to: {}, from: {}, next: Function) => {
      store.dispatch("session/checkLoggedIn");
      const isUserLoggedIn = store.getters["session/isAuthenticated"];
      const isAdmin = store.getters["session/isAdmin"];

      if (isUserLoggedIn && isAdmin == 1) {
        next({
          path: "/dashboard",
        });
      } else {
        next();
      }
    },
  },
  {
    path: "/orders",
    name: "Orders",
    component: Orders,
    meta: { requiresAuth: true },
  },
  {
    path: "/orders/:id",
    name: "Order",
    component: Order,
    meta: { requiresAuth: true },
  },
  {
    path: "/products/:id",
    name: "Product",
    component: Product,
    meta: { requiresAuth: true },
  },
];
