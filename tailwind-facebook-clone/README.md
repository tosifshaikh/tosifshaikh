
# Tailwind CSS

Basic tutorial on Tailwind CSS



## Ref links
- https://unsplash.com/
- https://randomuser.me/


## Production Set up
- npm init -y [This initializes the directory as a node js proj.]
- npm install -D tailwindcss postcss autoprefixer vite
- npx tailwindcss init
- Create a CSS file input.css add it to your html and edit it with this content.
@tailwind:base;
@tailwind:components;
@tailwind:utilities;
- In your tailwind config js file replace content : [] with content : ['*']
- Add "start": "vite" in your package.json
- Add "build":"vite build" in your package.json
- Run npm run start command to start a dev server

## Production Build
- npm run build