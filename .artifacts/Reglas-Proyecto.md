# Reglas del Proyecto — SmartCMS

## Reglas de desarrollo

1. **Seguir el plan maestro**: El desarrollo debe seguir el plan documentado en `.artifacts/Sprints.md`. Cada sprint contiene los archivos y componentes a crear.

2. **Código funcional 100%**: Todo el código generado debe ser completamente funcional. No se entrega código a medias o con placeholders.

3. **Explicaciones en lenguaje sencillo**: Las explicaciones deben usar analogías e ilustraciones fáciles de entender, como para un niño de 10 años.

4. **Commits ordenados**: Cada archivo creado o modificación importante = un commit. Los commits por sprint se ordenan del más antiguo al más reciente. El commit más reciente siempre se ve al final en el resumen.

5. **NotebookLM.md**: Al finalizar cada sprint, se crea/actualiza un artifact `.artifacts/NotebookLM.md` con material de aprendizaje complementario, incluyendo dudas surgidas durante la sesión.

6. **LinkedIn.md**: Al finalizar cada sprint, se crea/actualiza un artifact `.artifacts/LinkedIn.md` con una sugerencia de post para LinkedIn en lenguaje sencillo, sin primera persona, con 5+ hashtags y 3 sugerencias de screenshots clave.

7. **Pull Request**: Al finalizar el sprint, se proporciona título y descripción en inglés en formato markdown, con iconos descriptivos en cada punto clave. También se indica el nombre de la próxima rama en inglés (sin incluir "sprint").

8. **Código en inglés, explicaciones en español**: Todo el código (incluyendo comentarios) se escribe en inglés. Las explicaciones se dan en español.

9. **PasoAPaso.md**: Se crea un archivo `.artifacts/PasoAPaso.md` con instrucciones paso a paso para probar manualmente la aplicación completa.

## Convenios del proyecto

- **Stack**: Laravel 11 (backend), Vue 3 + TypeScript + Pinia + Tailwind v4 (frontend)
- **Testing**: Pest 3.0 con SQLite en memoria. Ejecutar `php vendor/bin/pest` en `backend/` antes de finalizar cambios.
- **Frontend**: Composition API con `<script setup>`, carpetas modulares por dominio (`api/`, `stores/`, `views/`, `components/`).
- **Backend**: Service Layer, Form Requests, Resources, JWT auth, roles admin/editor/viewer, Cloudinary para imágenes.
- **Infra**: Docker local, Railway (backend deploy), Netlify (frontend deploy).

## Flujo de trabajo

1. El agente (DeepSeek) realiza el trabajo correspondiente a un commit.
2. Explica qué se hizo y por qué, en lenguaje sencillo.
3. Pide confirmación para el commit.
4. El usuario autoriza con la palabra "commit".
5. El agente ejecuta `git add`, `git commit` con mensaje descriptivo en inglés.
6. Se repite hasta completar el sprint.
7. Al finalizar: crear NotebookLM.md, LinkedIn.md, PasoAPaso.md, y preparar PR description.
