import NProgress from 'nprogress';
import 'nprogress/nprogress.css';

// ------------------------------
// HELPERS
// ------------------------------

function getFormFromEvent(e) {
    const trigger = e.detail.elt;

    if (trigger.tagName === "FORM") {
        return trigger; // the form itself triggered it
    }

    return trigger.closest("form"); // button or inner element
}

export function setLoadingBtnMessageState(btn) {
    if (!btn) return;

    if (!btn.dataset.regularMessage) {
        btn.dataset.regularMessage = btn.innerText;
    }

    btn.innerText = btn.dataset.loadingMessage ?? "Loading...";
    btn.disabled = true;
    btn.classList.add("loading-btn");
}

export function clearLoadingBtnMessageState(btn) {
    if (!btn) return;

    if (btn.dataset.regularMessage) {
        btn.innerText = btn.dataset.regularMessage;
    }

    btn.disabled = false;
    btn.classList.remove("loading-btn");
}

export function setFormLoading(form) {
    if (!form) return;
    form.classList.add("form-loading");
}

export function clearFormLoading(form) {
    if (!form) return;
    form.classList.remove("form-loading");
}

// ------------------------------
// HTMX EVENT HANDLERS
// ------------------------------

document.body.addEventListener('htmx:beforeRequest', (e) => {
    console.log("🔥 HTMX request started", e);
    NProgress.start();

    const form = getFormFromEvent(e);
    // console.log('form', form);
    if (!form) return; // not a form request

    setFormLoading(form);

    const btn = form.querySelector("[data-loading-message]");
    setLoadingBtnMessageState(btn);
});

document.body.addEventListener('htmx:afterSwap', (e) => {
    console.log("✅ HTMX swap finished", e);
    NProgress.done();

    const form = getFormFromEvent(e);
    if (!form) return;

    clearFormLoading(form);

    const btn = form.querySelector("[data-loading-message]");
    clearLoadingBtnMessageState(btn);
});

document.body.addEventListener('htmx:responseError', (e) => {
    console.error("❌ HTMX error", e);
    NProgress.done();

    const form = getFormFromEvent(e);
    if (!form) return;

    clearFormLoading(form);

    const btn = form.querySelector("[data-loading-message]");
    clearLoadingBtnMessageState(btn);
});
