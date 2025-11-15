# Lineamientos para contribuciones

## Convenciones de nombres
- **Clases PHP (carpeta `negocio/`)**: se nombran en mayúsculas completas y representan entidades del dominio (por ejemplo, `ANIMALES`, `HACIENDA`, `LECHE`). Si se crean nuevas clases de negocio, utilice el mismo esquema en singular/plural según corresponda.
- **Métodos PHP**: siguen verbo + sustantivo en `lowerCamelCase` y se mantienen en español (ej. `mostrarInicio`, `crearEgreso`, `listarControlesFecha`). Evite mezclar idiomas o formatos distintos dentro de la jerarquía de herencia existente.
- **Tablas y vistas de base de datos**: los nombres se consultan en mayúsculas y entre comillas dobles (ej. `"ANIMALES"`, `"REPRODUCCION"`). Al agregar nuevas consultas respete ese formato para mantener consistencia con PostgreSQL.

## Estilo de documentación
- Cualquier cambio que añada métodos o clases debe incluir una breve descripción en el `README.md` siguiendo la estructura actual (sección de la clase con lista de métodos).
- Las interfaces en `interfaces/` deben indicar claramente qué acciones de negocio exponen en los comentarios o documentación relacionada.

