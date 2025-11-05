# 📘 Guía de trabajo del equipo

Esta guía tiene como objetivo orientar a los desarrolladores sobre cómo trabajar de manera ordenada en el proyecto **ReservaInstitucional**, usando ramas, commits claros y una estructura de archivos coherente.

---

## 🌿 Flujo de trabajo y ramas

- La rama principal de desarrollo es `develop`.  
- `main` se usa únicamente para versiones estables y deploy.  

### Flujo recomendado:

1. **Crear tu rama de desarrollo a partir de `develop`**
```bash
git switch develop
git pull origin develop
git switch -c dev-[nombre]

💡 **Notas importantes:**
- Solo se hace push a tu rama personal `dev-[nombre]`.  
- Ningún desarrollador hace push directo a `develop`.  
- Todo merge a `develop` se realiza mediante Pull Request y revisión.  

---


