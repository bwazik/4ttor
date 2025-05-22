/**
 *  Form Wizard
 */

'use strict';

(function () {

  // Init custom option check
  window.Helpers.initCustomOptionCheck();
  // Vertical Wizard
  // --------------------------------------------------------------------

  const wizardCreateDeal = document.querySelector('#wizard-create-deal');
  if (typeof wizardCreateDeal !== undefined && wizardCreateDeal !== null) {
    // Wizard form
    const wizardCreateDealForm = wizardCreateDeal.querySelector('#wizard-create-deal-form');
    // Wizard steps
    const wizardCreateDealFormStep1 = wizardCreateDealForm.querySelector('#deal-type');
    const wizardCreateDealFormStep2 = wizardCreateDealForm.querySelector('#deal-details');
    const wizardCreateDealFormStep3 = wizardCreateDealForm.querySelector('#deal-usage');
    const wizardCreateDealFormStep4 = wizardCreateDealForm.querySelector('#review-complete');
    // Wizard next prev button
    const wizardCreateDealNext = [].slice.call(wizardCreateDealForm.querySelectorAll('.btn-next'));
    const wizardCreateDealPrev = [].slice.call(wizardCreateDealForm.querySelectorAll('.btn-prev'));

    let validationStepper = new Stepper(wizardCreateDeal, {
      linear: true
    });
  }
})();
