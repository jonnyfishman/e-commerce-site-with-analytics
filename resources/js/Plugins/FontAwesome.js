import { library } from '@fortawesome/fontawesome-svg-core'
import { faXmark, faChevronDown, faChevronUp, faMagnifyingGlass, faBasketShopping, faUser, faHeart } from '@fortawesome/free-solid-svg-icons'
import { faCircle, faCircleCheck } from '@fortawesome/free-regular-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

library.add(faXmark, faCircle, faCircleCheck, faChevronUp, faChevronDown, faMagnifyingGlass, faBasketShopping, faUser, faHeart)
export default (app) => {
  app.component('font-awesome-icon', FontAwesomeIcon)
}
