/* ===========================================================================
   Const
   =========================================================================== */
export const CSS_CLASSES = {
  hide: 'is-hidden',
  active: 'is-active',
  open: 'is-open',
  loading: 'is-moved',
};

export const headers = new Headers({
  'X-Requested-With': 'XMLHttpRequest',
});

const credentials: string = 'include';

export const fetchParam = {
  headers,
  credentials,
};
