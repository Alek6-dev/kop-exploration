/* ===========================================================================
   Import
   =========================================================================== */

// JS
import todo from './todo';
import './modules/previewFileUpload';
import './modules/shareButton';
import './modules/flashMessage';
import './modules/newPassword';


// STYLE
import '../scss/app.scss';

/* ===========================================================================
   DOM Content Loaded
   =========================================================================== */

document.addEventListener(
  'DOMContentLoaded',
  () => {
    // Necessaty for show data-todo on page, adjust env condition if you want remove script on staging or pre-prod
    // @ts-ignore
    if (process.env.NODE_ENV !== 'production') {
      todo();
    }
  },
  false,
);
