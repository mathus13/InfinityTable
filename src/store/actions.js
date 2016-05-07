import store from './store'

export const clientsCommit = (site_ob) => {
  store.dispatch('CLIENTS_COMMIT', site_ob, 'clients')
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

export const appointmantsCommit = (site_ob) => {
  store.dispatch('APPTOINTMENTS_COMMIT', site_ob, 'appointments')
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

export const sitesCommit = (site_ob) => {
  store.dispatch('SITES_COMMIT', site_ob, 'sites')
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
