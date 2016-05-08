import store from './resources'

export const clientsCommit = (siteOb) => {
  store.dispatch('CLIENTS_COMMIT', siteOb, 'clients')
}

export const clientsSet = (sites) => {
  store.dispatch('CLIENTS_SET', sites, 'clients')
}

export const clientsDelete = (site) => {
  store.dispatch('CLIENTS_DELETE', site, 'clients')
}

export const clientsFetch = () => {
  store.dispatch('CLIENTS_FETCH', 'clients')
}

export const appointmantsCommit = (siteOb) => {
  store.dispatch('APPTOINTMENTS_COMMIT', siteOb, 'appointments')
}

export const appointmantsSet = (sites) => {
  store.dispatch('APPTOINTMENTS_SET', sites, 'appointments')
}

export const appointmantsDelete = (site) => {
  store.dispatch('APPTOINTMENTS_DELETE', site, 'appointments')
}

export const appointmentsFetch = () => {
  store.dispatch('APPOINTMENTS_FETCH', 'appointments')
}

export const sitesCommit = (siteOb) => {
  store.dispatch('SITES_COMMIT', siteOb, 'sites')
}

export const sitesSet = (sites) => {
  store.dispatch('SITES_SET', sites, 'sites')
}

export const sitesDelete = (site) => {
  store.dispatch('SITES_DELETE', site, 'sites')
}

export const sitesFetch = () => {
  store.dispatch('SITES_FETCH', 'sites')
}
