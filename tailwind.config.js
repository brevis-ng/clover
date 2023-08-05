/** @type {import('tailwindcss').Config} */
const defaultTheme = require("tailwindcss/defaultTheme");

export default {
    content: ["./resources/**/*.blade.php"],
    theme: {
        extend: {
            fontFamily: {
                sans: [
                    "Open Sans",
                    "sans-serif",
                    ...defaultTheme.fontFamily.sans,
                ],
                oswald: ["Oswald", "sans-serif"],
            },
            colors: {
                purple: "#6528F7",
            },
        },
        container: {
            center: true,
        },
    },
    plugins: [require("@tailwindcss/forms")],
    darkMode: "class",
};
