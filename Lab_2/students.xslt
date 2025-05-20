<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    version="1.0">

  <xsl:output method="html" encoding="UTF-8" indent="yes"/>

  <xsl:template match="/">
    <html>
      <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <title>Список студентів</title>
      </head>
      <body>
        <h2>Діючі студенти</h2>
        <table border="1">
          <tr>
            <th>ПІБ</th><th>Стать</th><th>Група</th>
          </tr>
          <xsl:for-each select="students/student">
            <tr>
              <td><xsl:value-of select="."/></td>
              <td><xsl:value-of select="@gender"/></td>
              <td><xsl:value-of select="@group"/></td>
            </tr>
          </xsl:for-each>
        </table>
      </body>
    </html>
  </xsl:template>

</xsl:stylesheet>