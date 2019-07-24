# Ftp Deploy

FTP 서버에 배포를 위한 CLI 도우미를 제공하여, 불필요한 파일의 첨부를 방지합니다.

```bash
node plugin/glam/deploy
```
> ```bash
> node plugin/glam/deploy --id=anomouse --password=password
> ```

* `--host`: FTP 주소
* `--id`: FTP 아이디
* `--password`: FTP 비밀번호
* `--root`: 웹 폴더 시작 경로(`www`, `httpd`, `public_html` 등, 서버 설정에 따라 상이)

기본 호출 방법은 위와 같으며 인자를 넘기지 않을 경우 동적으로 입력 할 수 있습니다.
