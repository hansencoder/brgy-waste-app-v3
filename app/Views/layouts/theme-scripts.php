<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Miranda+Sans:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>
<script>
tailwind.config = {
  theme: {
    extend: {
      colors: {
        border: 'hsl(214 20% 88%)',
        background: 'hsl(210 20% 97%)',
        foreground: 'hsl(215 60% 15%)',
        primary: {
          DEFAULT: 'hsl(215 60% 25%)',
          foreground: 'hsl(210 40% 98%)',
        },
        secondary: {
          DEFAULT: 'hsl(160 50% 40%)',
          foreground: 'hsl(0 0% 100%)',
        },
        accent: {
          DEFAULT: 'hsl(40 90% 55%)',
          foreground: 'hsl(215 60% 15%)',
        },
        muted: {
          DEFAULT: 'hsl(210 20% 93%)',
          foreground: 'hsl(215 16% 47%)',
        },
        card: {
          DEFAULT: 'hsl(0 0% 100%)',
          foreground: 'hsl(215 60% 15%)',
        },
        destructive: {
          DEFAULT: 'hsl(0 72% 51%)',
          foreground: 'hsl(0 0% 100%)',
        },
        success: {
          DEFAULT: 'hsl(142 71% 45%)',
          foreground: 'hsl(0 0% 100%)',
        },
        warning: {
          DEFAULT: 'hsl(40 90% 55%)',
          foreground: 'hsl(215 60% 15%)',
        },
        sidebar: {
          DEFAULT: 'hsl(215 60% 20%)',
          foreground: 'hsl(210 40% 90%)',
          accent: 'hsl(215 50% 30%)',
          border: 'hsl(215 40% 30%)',
        },
      },
      fontFamily: {
        sans: ['"Miranda Sans"', 'system-ui', '-apple-system', 'sans-serif'],
      },
      borderRadius: {
        lg: '0.5rem',
        md: 'calc(0.5rem - 2px)',
        sm: 'calc(0.5rem - 4px)',
      },
    },
  },
};
</script>
<style>
  body, input, button, select, textarea, p, h1, h2, h3, h4, h5, h6, span, a, label, table, td, th {
    font-family: 'Miranda Sans', sans-serif !important;
    font-optical-sizing: auto;
  }
</style>
