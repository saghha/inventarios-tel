import Vue from 'vue';
import Router from 'vue-router';

// Containers
import Full from '../containers/Full';

// Views
import Dashboard from '../views/Dashboard';
import Charts from '../views/Charts';
import Widgets from '../views/Widgets';
import Materiales from '../views/Materiales';
import NuevoPrestamo from '../views/NuevoPrestamo';
import PrestamosVigentes from '../views/PrestamosVigentes';
import Personas from '../views/Personas';
import Test from '../views/Test';
import MaterialesDisponibles from '../views/MaterialesDisponibles';
import MaterialesPrestados from '../views/MaterialesPrestados';
import Profile from '../views/Profile';
import PrestamosDevueltos from '../views/PrestamosDevueltos';

// Views - Components
import Buttons from '../views/components/Buttons';
import SocialButtons from '../views/components/SocialButtons';
import Cards from '../views/components/Cards';
import Forms from '../views/components/Forms';
import Modals from '../views/components/Modals';
import Switches from '../views/components/Switches';
import Tables from '../views/components/Tables';
import ChangePassword from '../views/ChangePassword';
import GoodTables from '../views/components/GoodTables'


// Views - Icons
import FontAwesome from '../views/icons/FontAwesome';
import SimpleLineIcons from '../views/icons/SimpleLineIcons';

// Views - Pages
import Page404 from '../views/pages/Page404';
import Page500 from '../views/pages/Page500';
import Login from '../views/pages/Login';
import Register from '../views/pages/Register';

Vue.use(Router);

export default new Router({
  mode: 'hash', // hash or hash = Demo is living in GitHub.io, so required!
  linkActiveClass: 'open active',
  scrollBehavior: () => ({ y: 0 }),
  routes: [
    {
      path: '/',
      redirect: '/dashboard',
      name: 'Home',
      component: Full,
      children: [
        {
          path: 'dashboard',
          name: 'Dashboard',
          component: Dashboard
        },
        {
          path: 'charts',
          name: 'Charts',
          component: Charts
        },
        {
          path: 'profile',
          name: 'Profile',
          component: Profile
        },
        {
          path: 'widgets',
          name: 'Widgets',
          component: Widgets
        },
        {
          path: 'materiales',
          name: 'Materiales',
          component: {
            render (c) { return c('router-view'); }
          },
          children:[
            {
              path: 'disponibles',
              name: 'MaterialesDisponibles',
              component: MaterialesDisponibles
            },
            {
              path: '',
              name: 'Materiales',
              component: Materiales
            },
            {
              path: 'prestados',
              name: 'MaterialesPrestados',
              component: MaterialesPrestados
            }
          ]
        },
        {
          path: 'persona',
          name: 'Personas',
          component: Personas
        },
        {
          path: 'test',
          name: 'Test',
          component: Test
        },
        {
          path: 'changepassword',
          name: 'ChangePassword',
          component: ChangePassword
        },
        {
          path: 'prestamos',
          name: 'Prestamos',
          component: {
            render (c) { return c('router-view'); }
          },
          children:[
            {
              path: 'nuevoprestamo',
              name: 'NuevoPrestamo',
              component: NuevoPrestamo
            },
            {
              path: 'vigentes',
              name: 'PrestamosVigentes',
              component: PrestamosVigentes
            },
            {
              path: 'devueltos',
              name: 'PrestamosDevueltos',
              component: PrestamosDevueltos
            }
          ]
        },
        {
          path: 'components',
          redirect: '/components/buttons',
          name: 'Components',
          component: {
            render (c) { return c('router-view'); }
          },
          children: [
            {
              path: 'buttons',
              name: 'Buttons',
              component: Buttons
            },
            {
              path: 'social-buttons',
              name: 'Social Buttons',
              component: SocialButtons
            },
            {
              path: 'cards',
              name: 'Cards',
              component: Cards
            },
            {
              path: 'forms',
              name: 'Forms',
              component: Forms
            },
            {
              path: 'modals',
              name: 'Modals',
              component: Modals
            },
            {
              path: 'switches',
              name: 'Switches',
              component: Switches
            },
            {
              path: 'tables',
              name: 'Tables',
              component: Tables
            }
          ]
        },
        {
          path: 'icons',
          redirect: '/icons/font-awesome',
          name: 'Icons',
          component: {
            render (c) { return c('router-view'); }
          },
          children: [
            {
              path: 'font-awesome',
              name: 'Font Awesome',
              component: FontAwesome
            },
            {
              path: 'simple-line-icons',
              name: 'Simple Line Icons',
              component: SimpleLineIcons
            }
          ]
        }
      ]
    },
    {
      path: '/pages',
      redirect: '/pages/p404',
      name: 'Pages',
      component: {
        render (c) { return c('router-view'); }
      },
      children: [
        {
          path: '404',
          name: 'Page404',
          component: Page404
        },
        {
          path: '500',
          name: 'Page500',
          component: Page500
        },
        {
          path: 'login',
          name: 'Login',
          component: Login
        },
        {
          path: 'register',
          name: 'Register',
          component: Register
        }
      ]
    }
  ]
});
