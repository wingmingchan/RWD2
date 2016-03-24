<xsl:stylesheet
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform" exclude-result-prefixes="xalan java" version="1.0"
    xmlns:java="http://xml.apache.org/xalan/java"
    xmlns:me="stylesheet"
    xmlns:node="http://www.upstate.edu/chanw/node"
    xmlns:xalan="http://xml.apache.org/xalan">
    <xsl:include href="site://_common/formats/Upstate/library/node-processing"/>
    <xsl:output encoding="UTF-8" indent="yes" method="html"/>
    <!-- cleanup -->
    <xsl:template match="//div[@id='storage']"/>
    <xsl:template match="//div[@id='slideshow-region']"/>
    <!-- add elements to be hidden and shown here -->
    <xsl:variable name="hide-elements">
        <element id="page-title" name="div"/>
        <element id="global-menu" name="div"/>
        <element id="global-link-script" name="div"/>
        <element id="search-form" name="div"/>
        <element id="top-outer" name="div"/>
        <element id="top-inner" name="div"/>
        <element id="bottom-inner" name="div"/>
        <element id="bottom-outer" name="div"/>
        <element id="bottom-outmost" name="div"/>
        <element id="page-level-override" name="div"/>
        <element id="site-title-without-link" name="div"/>
        <element id="javascript" name="div"/>
        <element id="theme" name="div"/>
        <element id="style" name="div"/>
        <element id="page-style" name="div"/>
        <element id="top-global-menu" name="div"/>
        <element id="logo" name="div"/>
        <element id="site-title" name="div"/>
        <element id="menubar" name="div"/>
        <element id="breadcrumb" name="div"/>
        <element id="site-search" name="div"/>
        <element id="left-column" name="div"/>
        <element id="right-column" name="div"/>
        <element id="custom-right-column" name="div"/>
        <element id="whatever" name="div"/>
        <element id="global-footer" name="div"/>
        <element id="last-modified" name="div"/>
        <element id="footer-contact" name="div"/>
        <element id="page-info" name="div"/>
        <element id="html-ng-app" name="div"/>
        <element id="body-ng-controller" name="div"/>
        <element id="code1" name="div"/>
        <element id="code2" name="div"/>
        <element id="code3" name="div"/>
    </xsl:variable>
    <xsl:template match="html">
        <xsl:variable name="html-ng-app">
            <xsl:value-of select="//div[@id='hide-html-ng-app']"/>
        </xsl:variable>
        <xsl:choose>
            <xsl:when test="not($html-ng-app='')">
                <html data-ng-app="{$html-ng-app}" lang="en">
                    <xsl:apply-templates select="node()"/>
                </html>
            </xsl:when>
            <xsl:otherwise>
                <html lang="en">
                    <xsl:apply-templates select="node()"/>
                </html>
            </xsl:otherwise>
        </xsl:choose>
    </xsl:template>
    <xsl:template match="body">
        <xsl:variable name="body-ng-controller">
            <xsl:value-of select="//div[@id='hide-body-ng-controller']"/>
        </xsl:variable>
        <xsl:choose>
            <xsl:when test="not($body-ng-controller='')">
                <body data-ng-controller="{$body-ng-controller}">
                    <xsl:apply-templates select="node()"/>
                </body>
            </xsl:when>
            <xsl:otherwise>
                <body>
                    <xsl:apply-templates select="node()"/>
                </body>
            </xsl:otherwise>
        </xsl:choose>
    </xsl:template>
    <xsl:template match="@*|node()" priority="-1">
        <xsl:variable name="name" select="name(.)"/>
        <xsl:variable name="id" select="@id"/>
        <!-- hide-whatever -->
        <xsl:variable name="hideid" select="concat('hide-', substring-after(@id, 'show-'))"/>
        <!-- content of hide-whatever -->
        <xsl:variable name="hidden" select="//node()[name(.)=$name and @id=$hideid]/node()"/>
        <xsl:choose>
            <!-- remove hide-whatever -->
            <xsl:when test="xalan:nodeset($hide-elements)/element[@name=$name and $id=concat('hide-', @id)]"/>
            <!-- show the content of hide-whatever in the show-whatever area -->
            <xsl:when test="xalan:nodeset($hide-elements)/element[@name=$name and $id=concat('show-', @id)]">
                <xsl:apply-templates select="$hidden"/>
            </xsl:when>
            <!-- other nodes and attributes -->
            <xsl:otherwise>
                <xsl:copy>
                    <xsl:apply-templates select="@*|node()"/>
                </xsl:copy>
            </xsl:otherwise>
        </xsl:choose>
    </xsl:template>
    <xsl:template match="//div[@id='hide-menubar']/nav/ul/li">
        <xsl:variable name="root-folder">
            <xsl:value-of select="//div[@id='root-folder']"/>
        </xsl:variable>
        <xsl:variable name="id">
            <xsl:value-of select="@id"/>
        </xsl:variable>
        <!-- used to highlight the root tab -->
        <xsl:if test="$id!='' and $id=$root-folder">
            <li>
                <xsl:attribute name="id">
                    <xsl:value-of select="$id"/>
                </xsl:attribute>
                <xsl:attribute name="class">currentMenuItem</xsl:attribute>
                <xsl:apply-templates select="./node()"/>
            </li>
        </xsl:if>
        <!-- used to remove the separator on the right of the last tab -->
        <xsl:if test="$id='' or $id!=$root-folder">
            <xsl:choose>
                <xsl:when test="(position()=last()) and (@class='separator')"/>
                <!-- reconstruct li for empty ul child, removing ul -->
                <xsl:when test="./ul[not(node())]">
                    <li>
                        <a>
                            <xsl:attribute name="href">
                                <xsl:value-of select="a/@href"/>
                            </xsl:attribute>
                            <xsl:value-of select="."/>
                        </a>
                    </li>
                </xsl:when>
                <xsl:otherwise>
                    <xsl:copy-of select="."/>
                </xsl:otherwise>
            </xsl:choose>
        </xsl:if>
    </xsl:template>
    <!-- used to remove excessive whitespaces -->
    <xsl:template match="body//text()[not(ancestor::pre)]">
        <xsl:variable name="curtext">
            <xsl:value-of select="."/>
        </xsl:variable>
        <xsl:variable name="nonewline">
            <xsl:value-of select="java:replaceAll($curtext,'(\n){2,}','')"/>
        </xsl:variable>
        <xsl:variable name="notab">
            <xsl:value-of select="java:replaceAll($nonewline,'\t','')"/>
        </xsl:variable>
        <xsl:variable name="noextraspace">
            <xsl:value-of select="java:replaceAll($notab,'(\s){2}','')"/>
        </xsl:variable>
        <xsl:value-of select="$noextraspace"/>
    </xsl:template>
    <xsl:template match="title">
        <xsl:variable name="title">
            <xsl:value-of select="."/>
        </xsl:variable>
        <title>
            <xsl:value-of select="java:replaceAll($title,' \|  \| ','')"/>
        </title>
    </xsl:template>
    <!-- process the a tag and add icons -->
    <xsl:template match="//a[ancestor::div[@id='center-only']|ancestor::div[@id='center-right']|ancestor::div[@id='left-center']|ancestor::div[@id='full-width']][not(img) and not(div)]">
        <xsl:choose>
            <xsl:when test="//text()">
                <xsl:call-template name="node:process-a">
                    <xsl:with-param name="node" select="."/>
                </xsl:call-template>
            </xsl:when>
            <xsl:otherwise>
                <a>
                    <xsl:apply-templates select="@*|node()"/>
                </a>
            </xsl:otherwise>
        </xsl:choose>
    </xsl:template>
</xsl:stylesheet>