---
name: api-doc
description: Generate or update API Platform documentation
---

Generate or update the API documentation for the Symfony API Platform backend.

## Tasks to perform:

1. **Check current API state**
   - List all API Platform resources in `src/*/Infrastructure/ApiPlatform/`
   - Identify any missing or outdated documentation

2. **Generate OpenAPI spec** (if applicable)
   ```bash
   cd api && php bin/console api:openapi:export --output=public/api-docs.json
   ```

3. **Verify documentation completeness**
   - Check that all endpoints have descriptions
   - Verify request/response examples exist
   - Ensure authentication requirements are documented

4. **Update if needed**
   - Add missing `#[ApiResource]` descriptions
   - Add `#[OA\Response]` annotations for edge cases
   - Document query parameters and filters

Report what was found and any updates made.
