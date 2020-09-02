import Vue from "vue";
import VueApollo from "vue-apollo";
import {
  createApolloClient,
  restartWebsockets,
  // @ts-ignore
} from "vue-cli-plugin-apollo/graphql-client";

import { setContext } from "apollo-link-context";

// Install the vue plugin
Vue.use(VueApollo);

// Name of the localStorage item
const AUTH_TOKEN = "apollo-token";

// Http endpoint
const httpEndpoint =
  process.env.VUE_APP_GRAPHQL_HTTP || "http://localhost:4000/graphql";

const authLink = setContext((_, { headers }) => {
  // get the authentication token from local storage if it exists
  let authHeader = "";
  if (localStorage.getItem(AUTH_TOKEN) === null) {
    authHeader = "";
  } else {
    let token = localStorage.getItem(AUTH_TOKEN);
    authHeader = `Bearer ${token}`;
  }

  // return the headers to the context so httpLink can read them
  return {
    headers: {
      ...headers,
      authorization: authHeader,
    },
  };
});

const defaultOptions = {
  httpEndpoint,
  wsEndpoint: null,
  tokenName: AUTH_TOKEN,
  persisting: false,
  websocketsOnly: false,
  ssr: false,
  link: authLink,
  // cache: myCache,
  // getAuth: () => {},
  // apollo: { ... },
  // clientState: { resolvers: { ... }, defaults: { ... } }
};

// Create apollo client
export const { apolloClient, wsClient } = createApolloClient({
  ...defaultOptions,
  // ...options
});
apolloClient.wsClient = wsClient;

// Call this in the Vue app file
export function createProvider(options: any = {}) {
  // Create vue apollo provider
  const apolloProvider = new VueApollo({
    defaultClient: apolloClient,
    defaultOptions: {
      $query: {
        // fetchPolicy: "cache-and-network",
      },
    },
    errorHandler(error) {
      // eslint-disable-next-line no-console
      console.log(
        "%cError",
        "background: red; color: white; padding: 2px 4px; border-radius: 3px; font-weight: bold;",
        error.message
      );
    },
  });

  return apolloProvider;
}

// Manually call this when user log in
export async function onLogin(apolloClient: any, token: string) {
  if (typeof localStorage !== "undefined" && token) {
    localStorage.setItem(AUTH_TOKEN, token);
  }
  if (apolloClient.wsClient) restartWebsockets(apolloClient.wsClient);
  try {
    await apolloClient.resetStore();
  } catch (e) {
    // eslint-disable-next-line no-console
    console.log("%cError on cache reset (login)", "color: orange;", e.message);
  }
}

// Manually call this when user log out
export async function onLogout(apolloClient: any) {
  if (typeof localStorage !== "undefined") {
    localStorage.removeItem(AUTH_TOKEN);
  }
  if (apolloClient.wsClient) restartWebsockets(apolloClient.wsClient);
  try {
    await apolloClient.resetStore();
  } catch (e) {
    // eslint-disable-next-line no-console
    console.log("%cError on cache reset (logout)", "color: orange;", e.message);
  }
}
