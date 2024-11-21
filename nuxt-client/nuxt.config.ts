// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({

  hooks: {
    'pages:extend'(pages) {
      // add a route
      pages.push({
        name: 'profile-edit',
        path: '/profile/:hash/edit',
        file: '~/pages/profile/edit.vue'
      })
    }
  },

  compatibilityDate: '2024-04-03',
  devtools: { enabled: true },

  // CSS
  css: ['~/assets/css/app.css'],
  postcss: {
    plugins: {
      tailwindcss: {},
      autoprefixer: {},
    },
  },

  // APP 
  app: {
    head: {
      title: '.:: Nuxt Front-end with Laravel REST API ::.',
      meta: [
        { name: 'description', content: 'Laravel REST API as backend with Nuxt as Front-end clined' }
      ],
      charset: 'utf-8',
      viewport: 'width=device-width, initial-scale=1',
      link: [
        {
          rel: 'preconnect',
          href: 'https://fonts.bunny.net',
        },
        {
          rel: 'stylesheet',
          href: 'https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap',
          crossorigin: '',
        },
      ],
    },
  },

  devServer: {
    host: 'http://localhost:8000'
  },
  modules: ["nuxt-auth-sanctum", "@nuxtjs/tailwindcss", '@pinia/nuxt','pinia-plugin-persistedstate'],
  sanctum: {
    baseUrl: 'http://localhost:8000/api',
    endpoints: {
      csrf: '/sanctum/csrf-cookie',
      login: '/login',
      logout: '/logout',
      user: '/user',
    },
    redirect: {
      onLogin: '/',
      onAuthOnly: '/login',
      onGuestOnly: '/'
    },
  },

  // Include Dirs 
  imports: {
    dirs: [
      'composables',
      'composables/*/index.{ts,js,mjs,mts}',
      'composables/**'
    ]
  },

  // RunTime Config
  runtimeConfig: {
    public: {
      BACKEND_URL: '',
    },
  }
})