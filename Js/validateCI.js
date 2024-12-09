document.getElementById('dni').addEventListener('input', function () {
    const dni = this.value;
    const isValid = validateEcuadorianDNI(dni);
    if (!isValid) {
        this.setCustomValidity('Cédula inválida');
    } else {
        this.setCustomValidity('');
    }
});

function validateEcuadorianDNI(dni) {
    if (dni.length !== 10) return false;

    const provinceCode = parseInt(dni.substring(0, 2), 10);
    if (provinceCode < 1 || provinceCode > 24) return false;

    const thirdDigit = parseInt(dni[2], 10);
    if (thirdDigit < 0 || thirdDigit > 5) return false;

    const coefficients = [2, 1, 2, 1, 2, 1, 2, 1, 2];
    let sum = 0;

    for (let i = 0; i < coefficients.length; i++) {
        let value = coefficients[i] * parseInt(dni[i], 10);
        if (value >= 10) value -= 9;
        sum += value;
    }

    const verifier = parseInt(dni[9], 10);
    const calculatedVerifier = (10 - (sum % 10)) % 10;

    return verifier === calculatedVerifier;
}