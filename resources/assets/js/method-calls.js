import TotalsRepository from './budget/src/repositories/TotalsRepository'
import FilterRepository from './budget/src/repositories/FilterRepository'

store.getUser();
store.getEnvironment();
store.getAccounts();
store.setHeights();
store.getBudgets();
store.getUnassignedBudgets();
store.getFavouriteTransactions();
store.setDefaultTab();
TotalsRepository.getSideBarTotals();
store.getSavedFilters();
FilterRepository.resetFilter();
store.setDefaultTransactionPropertiesToShow()

$(window).on('load', function () {
    // $(".main").css('display', 'block');
    // $("footer, #navbar").css('display', 'flex');
    // $("#page-loading").hide();
    //Uncomment after refactor
    // smoothScroll.init();
});
