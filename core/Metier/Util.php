<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * Util
 *
 * @ORM\Table(name="util", indexes={@ORM\Index(name="fk_UTIL_PRF1_idx", columns={"idPRF"})})
 * @ORM\Entity
 */
class Util
{
    /**
     * @var integer
     *
     * @ORM\Column(name="IDUTIL", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idutil;

    /**
     * @var string
     *
     * @ORM\Column(name="UTILOG", type="string", length=45, nullable=true)
     */
    private $utilog;

    /**
     * @var string
     *
     * @ORM\Column(name="UTIPWD", type="string", length=45, nullable=true)
     */
    private $utipwd;

    /**
     * @var string
     *
     * @ORM\Column(name="UTINOM", type="string", length=45, nullable=true)
     */
    private $utinom;

    /**
     * @var string
     *
     * @ORM\Column(name="UTIPNOM", type="string", length=45, nullable=true)
     */
    private $utipnom;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="UTIDCR", type="date", nullable=true)
     */
    private $utidcr;

    /**
     * @var string
     *
     * @ORM\Column(name="UTImat", type="decimal", precision=10, scale=0, nullable=true)
     */
    private $utimat;

    /**
     * @var string
     *
     * @ORM\Column(name="OPECOD", type="decimal", precision=10, scale=0, nullable=true)
     */
    private $opecod;

    /**
     * @var string
     *
     * @ORM\Column(name="OPEEXE", type="decimal", precision=10, scale=0, nullable=true)
     */
    private $opeexe;

    /**
     * @var string
     *
     * @ORM\Column(name="PROFCOD", type="decimal", precision=10, scale=0, nullable=true)
     */
    private $profcod;

    /**
     * @var string
     *
     * @ORM\Column(name="ETATCOD", type="decimal", precision=10, scale=0, nullable=true)
     */
    private $etatcod;

    /**
     * @var integer
     *
     * @ORM\Column(name="idPRF", type="integer", nullable=false)
     */
    private $idprf;



    /**
     * Get idutil
     *
     * @return integer
     */
    public function getIdutil()
    {
        return $this->idutil;
    }

    /**
     * Set utilog
     *
     * @param string $utilog
     *
     * @return Util
     */
    public function setUtilog($utilog)
    {
        $this->utilog = $utilog;

        return $this;
    }

    /**
     * Get utilog
     *
     * @return string
     */
    public function getUtilog()
    {
        return $this->utilog;
    }

    /**
     * Set utipwd
     *
     * @param string $utipwd
     *
     * @return Util
     */
    public function setUtipwd($utipwd)
    {
        $this->utipwd = $utipwd;

        return $this;
    }

    /**
     * Get utipwd
     *
     * @return string
     */
    public function getUtipwd()
    {
        return $this->utipwd;
    }

    /**
     * Set utinom
     *
     * @param string $utinom
     *
     * @return Util
     */
    public function setUtinom($utinom)
    {
        $this->utinom = $utinom;

        return $this;
    }

    /**
     * Get utinom
     *
     * @return string
     */
    public function getUtinom()
    {
        return $this->utinom;
    }

    /**
     * Set utipnom
     *
     * @param string $utipnom
     *
     * @return Util
     */
    public function setUtipnom($utipnom)
    {
        $this->utipnom = $utipnom;

        return $this;
    }

    /**
     * Get utipnom
     *
     * @return string
     */
    public function getUtipnom()
    {
        return $this->utipnom;
    }

    /**
     * Set utidcr
     *
     * @param \DateTime $utidcr
     *
     * @return Util
     */
    public function setUtidcr($utidcr)
    {
        $this->utidcr = $utidcr;

        return $this;
    }

    /**
     * Get utidcr
     *
     * @return \DateTime
     */
    public function getUtidcr()
    {
        return $this->utidcr;
    }

    /**
     * Set utimat
     *
     * @param string $utimat
     *
     * @return Util
     */
    public function setUtimat($utimat)
    {
        $this->utimat = $utimat;

        return $this;
    }

    /**
     * Get utimat
     *
     * @return string
     */
    public function getUtimat()
    {
        return $this->utimat;
    }

    /**
     * Set opecod
     *
     * @param string $opecod
     *
     * @return Util
     */
    public function setOpecod($opecod)
    {
        $this->opecod = $opecod;

        return $this;
    }

    /**
     * Get opecod
     *
     * @return string
     */
    public function getOpecod()
    {
        return $this->opecod;
    }

    /**
     * Set opeexe
     *
     * @param string $opeexe
     *
     * @return Util
     */
    public function setOpeexe($opeexe)
    {
        $this->opeexe = $opeexe;

        return $this;
    }

    /**
     * Get opeexe
     *
     * @return string
     */
    public function getOpeexe()
    {
        return $this->opeexe;
    }

    /**
     * Set profcod
     *
     * @param string $profcod
     *
     * @return Util
     */
    public function setProfcod($profcod)
    {
        $this->profcod = $profcod;

        return $this;
    }

    /**
     * Get profcod
     *
     * @return string
     */
    public function getProfcod()
    {
        return $this->profcod;
    }

    /**
     * Set etatcod
     *
     * @param string $etatcod
     *
     * @return Util
     */
    public function setEtatcod($etatcod)
    {
        $this->etatcod = $etatcod;

        return $this;
    }

    /**
     * Get etatcod
     *
     * @return string
     */
    public function getEtatcod()
    {
        return $this->etatcod;
    }

    /**
     * Set idprf
     *
     * @param integer $idprf
     *
     * @return Util
     */
    public function setIdprf($idprf)
    {
        $this->idprf = $idprf;

        return $this;
    }

    /**
     * Get idprf
     *
     * @return integer
     */
    public function getIdprf()
    {
        return $this->idprf;
    }
}
