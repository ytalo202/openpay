const paymentMethods = document.querySelector('#payment-methods');

[...paymentMethods.children].forEach(element => {
  element.addEventListener('click', () => {
    [...paymentMethods.children].forEach(el => {
      const method = document.querySelector(`#${el.dataset.method}`);
      el.classList.remove('payment-methods__method--selected');
      method.style.display = 'none';
    });

    element.classList.add('payment-methods__method--selected');
    const method = document.querySelector(`#${element.dataset.method}`);

    method.style.display = 'flex';
  });
});
