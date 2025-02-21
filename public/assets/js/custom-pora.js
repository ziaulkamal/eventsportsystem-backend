const genderFromKtp = (ktp) => {
    const day = parseInt(ktp.substring(6, 8), 10);
    return day > 40 ? "female" : "male";
};

const birthdateFromKtp = (ktp) => {
    if (ktp.length !== 16) return null;
    const yearBirthDate = parseInt(ktp.substring(10, 12), 10);
    const thisYear = parseInt(new Date().getFullYear().toString().slice(-2), 10);
    const pushAddYear = yearBirthDate >= thisYear ? 1900 : 2000;
    const year = yearBirthDate + pushAddYear;
    const month = parseInt(ktp.substring(8, 10), 10) - 1;
    let day = parseInt(ktp.substring(6, 8), 10);
    if (day > 40) day -= 40;
    try {
        return new Date(year, month, day);
    } catch (error) {
        return null;
    }
};
