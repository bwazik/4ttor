/**
 *  Form Wizard
 */

"use strict";

(function () {
    const wizardModernVertical = document.querySelector(
            ".wizard-modern-vertical"
        ),
        wizardModernVerticalBtnNextList = [].slice.call(
            wizardModernVertical.querySelectorAll(".btn-next")
        ),
        wizardModernVerticalBtnPrevList = [].slice.call(
            wizardModernVertical.querySelectorAll(".btn-prev")
        );
    if (
        typeof wizardModernVertical !== undefined &&
        wizardModernVertical !== null
    ) {
        const modernVerticalStepper = new Stepper(wizardModernVertical, {
            linear: false,
        });
        if (wizardModernVerticalBtnNextList) {
            wizardModernVerticalBtnNextList.forEach(
                (wizardModernVerticalBtnNext) => {
                    wizardModernVerticalBtnNext.addEventListener(
                        "click",
                        (event) => {
                            modernVerticalStepper.next();
                        }
                    );
                }
            );
        }
        if (wizardModernVerticalBtnPrevList) {
            wizardModernVerticalBtnPrevList.forEach(
                (wizardModernVerticalBtnPrev) => {
                    wizardModernVerticalBtnPrev.addEventListener(
                        "click",
                        (event) => {
                            modernVerticalStepper.previous();
                        }
                    );
                }
            );
        }
    }
})();
